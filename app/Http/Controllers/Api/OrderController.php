<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\AuthLoginRequest;
use App\Http\Requests\Api\OrderCheckRequest;
use App\Http\Requests\Api\OrderIndexRequest;
use App\Http\Requests\Api\OrderStoreRequest;
use App\Http\Requests\Api\OrderUpdateRequest;
use App\Http\Requests\Api\UserUpdateRequest;
use App\Http\Resources\UserResource;
use App\Models\DiscountCard;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\PriceType;
use App\Models\Product;
use App\Models\PromoCode;
use App\Models\Store;
use App\Models\StoreProductPromotion;
use App\Models\User;
use App\Models\WebkassaCheck;
use App\Services\WebKassa\WebKassaService;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index(OrderIndexRequest $request)
    {

        $orders = Order::query()
            ->where('orders.store_id',Auth::user()->store_id)
            ->whereNotNull('check_number')
            ->when($request->has('date_from'),function ($q){
                $q->whereDate('created_at','>=',request('date_from'));
            })
            ->when($request->has('date_to'),function ($q){
                $q->whereDate('created_at','<=',request('date_to'));
            })
            ->when($request->has('search'),function ($q){
                $q->where('orders.id','LIKE',request('search').'%');
            })
            ->whereNotNull('check_number')
            ->with(['products','products.product','store','organization','storage','counteragent','webkassaCheck'])
            ->latest()
            ->get();
        return response()->json($orders);
    }

    public function history(OrderIndexRequest $request)
    {
        $orders = Order::query()
            ->where('orders.store_id',Auth::user()->store_id)
            ->whereNotNull('check_number')
            ->when($request->has('date_from'),function ($q){
                $q->whereDate('created_at','>=',request('date_from'));
            })
            ->when($request->has('date_to'),function ($q){
                $q->whereDate('created_at','<=',request('date_to'));
            })
            ->when($request->has('search'),function ($q){
                $q->where('orders.id','LIKE',request('search').'%');
            })
            ->with(['products','products.product','store','organization','storage','counteragent','webkassaCheck'])
            ->whereNotNull('check_number')
            ->latest()
            ->get();
        return response()->json($orders);
    }

    public function store(OrderStoreRequest $request)
    {
        $data = [];
        $data['storage_id'] = Auth::user()->storage_id;
        $data['store_id'] = Auth::user()->store_id;
        $data['organization_id'] = Auth::user()->organization_id;
        $discountCard = null;
        $promoCode = $request->has('phone') ? PromoCode::where('phone',$request->get('phone'))->whereDate('start','<=',now())->whereDate('end','>=',now())->first() : null;
        $store = Store::find($data['store_id']);

        try {
            if (!$store) throw new("магазин не найден");
            if ($request->has('phone')){
                $discountCard = DiscountCard::where('store_id',$data['store_id'])->where('phone',$request->get('phone'))->first();
//                if (!$discountCard) throw new Exception('Пользователь не найден');
            }
            $order = Auth::user()->orders()->create(array_merge($request->validated(),$data));
            if ($request->has('products'))
            {
                foreach ($request->get('products') as $item) {
                    $discount = 0;
                    if ($promoCode) $discount = $promoCode->discount;

                    $product = Product::find($item['product_id']);
                    if (!$product) continue;
                    $productPriceType = $product->prices()->where('price_type_id', 3)->first();
                    $discount = $discount + $product->discount ;
                    if (!$productPriceType) continue;
                    $product->update(['remainder' => $product->remainder - $item['count']]);
                    if ($product->measure == 2){
                        $item['count'] = round( $item['count'] ,3 );
                    }
//                    //временный скидка
//                    $dateDiscount = StoreProductPromotion::query()
//                        ->whereDate('date_from','<=',now())
//                        ->whereDate('date_to','>=',now())
//                        ->where('store_id',$store->id)
//                        ->where('online_sale',$request->get('online_sale'))
//                        ->where('product_id',$product->id)
//                        ->first();

//                    if ($dateDiscount){
//                        if ($dateDiscount->discount > 0){
//                            $item['discount_price'] = ($productPriceType->price / 100) * $dateDiscount->discount ;
//                            $item['price'] = $productPriceType->price -  $item['discount_price'] ;
//                        }else{
//                            $item['price'] = $dateDiscount->price;
//                        }
//                    }else{
                        $item['discount_price'] = 0;
                        if ($discount > 0){
                            $item['discount_price'] = ceil(($productPriceType->price / 100) * $discount) ;
                            $item['price'] = ceil( $productPriceType->price -  $item['discount_price']) ;
                        }else{
                            $item['price'] = $productPriceType->price ;
                        }
//                    }

                    //скидка дисконта
                    if ($discountCard){
                        $discountPrice = ceil( ( $item['price'] / 100) * $discountCard->discount);

                        $item['price'] = $item['price'] - $discountPrice;
                        $item['discount_price'] = $item['discount_price'] + $discountPrice;
                    }
                    $item['all_price'] = $item['count'] * $item['price'];




                    //скидка на 5тую позицию
                    if ($store->discount_position AND $product->measure == 1){
                        $item['count'] += floor( $item['count'] / 4);
                    }

                    $order->products()->create($item);
                    unset($item);

                }
                $totalPrice =  $order->products()->sum('all_price');


                $promotions = StoreProductPromotion::query()
                    ->whereDate('date_from','<=',now())
                    ->whereDate('date_to','>=',now())
                    ->where('online_sale',$request->get('online_sale'))
                    ->where('store_id',$store->id)
                    ->where('price_condition','<=',$totalPrice)
                    ->get();

                foreach ($promotions as $promotion) {
                    $all_price = $promotion->count * $promotion->price;
                    $order->products()->updateOrCreate(['product_id' => $promotion->product_id,'order_id' => $order->id],[
                        'product_id' => $promotion->product_id,
                        'order_id' => $order->id,
                        'count' => $promotion->count,
                        'price' => $promotion->price,
                        'all_price' => $all_price,
                        'comment' => 'подарок'
                    ]);
                    $totalPrice += $all_price;
                }

//
//                if ($totalPrice >= 5000 AND $request->get('online_sale') == 0){
//
//                    //2556
//
//                    $order->products()->create([
//                        'product_id' => 2556,
//                        'order_id' => $order->id,
//                        'count' => 1,
//                        'price' => 1,
//                        'all_price' => 1,
//                        'comment' => 'подарок'
//                    ]);
//                    $totalPrice += 1;
//                }


                //кешбэк
                if ($discountCard){
                    $discountCard->increment(
                        'cashback_total_price', ($totalPrice / 100) * $discountCard->cashback
                    );
                    $order->discount_cashback = ($totalPrice / 100) * $discountCard->discount;
                }
                $order->product_history = $order->products()->select('product_id','count','price','all_price','comment')->get()->toArray();
                $order->total_discount_price = $order->products()->sum('discount_price');
                $order->total_price = $totalPrice;
                $order->save();

            }else{
                throw new Exception("products not found");
            }

            return response()->json($order);
        }catch (Exception $exception)
        {
            return  response()->json(['message' => $exception->getMessage()],400);
        }

    }

    public function update(OrderUpdateRequest $request,Order $order)
    {

        if ($request->has('products'))
        {
            $counteragent = $order->counteragent;
            $priceType = $counteragent ? $counteragent->priceType : PriceType::find(1);
            $discount = $counteragent ? $counteragent->discount : 0;

            foreach ($request->get('products') as $item) {
                if ($item['event'] == 'delete')
                {
                    $order->products()->where('product_id',$item['product_id'])->delete();
                }
                if ($item['event'] == 'create')
                {
                    $product = Product::find($item['product_id']);
                    if (!$product) continue;
                    $productPriceType = $product->prices()->where('price_type_id', $priceType->id)->first();
                    $discount = $discount == 0 ? $product->discount : $discount;
                    if (!$productPriceType) continue;
                    $product->update(['remainder' => $product->remainder - $item['count']]);
                    $productCounteragentPrice = $counteragent ? $product->counteragentPrices()->where('counteragent_id',$counteragent->id )->first() : null;
                    if ($productCounteragentPrice) {
                        $item['price'] = $productCounteragentPrice->price;
                    } else {
                        $discount = $discount == 0 ? $product->discount : $discount;
                        if ($discount > 0){
                            $item['price'] = ($productPriceType->price / 100) * $discount;
                        }else{
                            $item['price'] = $productPriceType->price ;
                        }
                    }
                    $item['all_price'] =   $item['price'] * $item['count'];

                    $order->products()->updateOrCreate(['product_id' => $product->id,'order_id' => $order->id],$item);
                }
                if ($item['event'] == 'update'){
                    $orderProduct = $order->products()->where('product_id',$item['product_id'])->first();
                    $item['all_price'] = $item['count'] * $orderProduct->price;
                    $order->products()->updateOrCreate(['product_id' => $product->id,'order_id' => $order->id],$item);
                }

            }
            $order->update(['product_history' => $order->products()->select('product_id','count','price','all_price','comment')->toArray() ]);
        }

        $order->update($request->validated());

        return response()->json($order);
    }

    public function delete(Order $order)
    {
        $order->delete();
        return response()->json($order);
    }

    public function check($orderId,OrderCheckRequest $request)
    {
        $order = Order::find($orderId);
        if (!$order){
            return response()->json(['message' =>'ошибка, попробуйте заново создать заявку'],400);
        }
        try {
            $sum = 0;
            foreach ($request->get('payments') as $item) {
                $sum += $item['Sum'];
            }

            $order->payments = $request->get('payments');
            $order->give_price = $sum - $order->total_price;
            $order->save();

            $data =  WebKassaService::checkOrder($order,$request->get('payments'));
            return response()->json($data);
        }catch (\Exception $exception){


            foreach ($order->products as $orderProduct)
            {
                $product = $orderProduct->product;
                $count =  (int) $product->remainder + (int)$orderProduct->count;

                $product->update(['remainder' => $count]);
            }

            $order->delete();
            return response()->json(['message' => $exception->getMessage()],400);
        }
    }

    public function printCheck(Order $order)
    {
        try {
            $check = WebkassaCheck::where('order_id',$order->id)->whereNotNull('check_number')->latest()->first();


            if (!$check)
            {
                throw new Exception('чек не найден');
            }

           $data =  WebKassaService::printFormat(Auth::user(),$check->number);
            return response()->json($data);
        }catch (\Exception $exception){

            return response()->json(['message' => $exception->getMessage()],400);
        }
    }

    public function printCheckFormat(Order $order)
    {
        try {
            $check = WebkassaCheck::where('order_id',$order->id)->whereNotNull('check_number')->latest()->first();


            if (!$check)
            {
                throw new Exception('чек не найден');
            }

           $data =  WebKassaService::printFormat($order->user,$check->number);

            return response()->view('pdf.check',compact('data'));
        }catch (\Exception $exception){

            return response()->json(['message' => $exception->getMessage()],400);
        }
    }


}
