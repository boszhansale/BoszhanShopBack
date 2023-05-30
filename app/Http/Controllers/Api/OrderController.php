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
use App\Models\Store;
use App\Models\User;
use App\Services\WebKassa\WebKassaService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index(OrderIndexRequest $request)
    {
        $orders = Order::query()
            ->where('orders.user_id',Auth::id())
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
            ->latest()
            ->get();
        return response()->json($orders);
    }

    public function history(OrderIndexRequest $request)
    {
        $orders = Order::query()
            ->where('orders.user_id',Auth::id())
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
        $store = Store::find($data['store_id']);
        try {
            if (!$store) throw new("магазин не найден");
            if ($request->has('discount_phone')){
                $discountCard = DiscountCard::where('store_id',$data['store_id'])->where('phone',$request->get('discount_phone'))->first();
                if (!$discountCard) throw new Exception('Пользователь не найден');
            }
            $order = Auth::user()->orders()->create(array_merge($request->validated(),$data));
            if ($request->has('products'))
            {
                $counteragent = $order->counteragent;
                $priceType = $counteragent ? $counteragent->priceType : PriceType::find(1);
                $discount = $counteragent ? $counteragent->discount : 0;

                foreach ($request->get('products') as $item) {
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
                        //скидка дисконта
                        if ($discountCard){
                            $item['price'] = ( $item['price'] / 100) * $discountCard->discount;
                        }
                    }
                    $item['all_price'] = $item['count'] * $item['price'];
                    //скидка на 5тую позицию
                    if ($store->discount_position AND $product->measure == 1){
                        $item['count'] += floor( $item['count'] / 4);
                    }

                    $order->products()->updateOrCreate(['product_id' => $product->id,'order_id' => $order->id],$item);
                }
                $totalPrice =  $order->products()->sum('all_price');
                //кешбэк
                if ($discountCard){
                    $discountCard->increment(
                        'cashback_total_price', ($totalPrice / 100) * $discountCard->cashback
                    );
                    $order->discount_cashback = ($totalPrice / 100) * $discountCard->discount;
                }
                $order->product_history = $order->products()->select('product_id','count','price','all_price','comment')->get()->toArray();
                $order->total_price = $totalPrice;
                $order->save();

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
                    $item['all_price'] = $item['count'] * $item['price'];

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

    public function check(Order $order,OrderCheckRequest $request)
    {
        try {
           $data =  WebKassaService::checkOrder($order,$request->get('payments'));
            return response()->json($data);
        }catch (\Exception $exception){
            return response()->json(['message' => $exception->getMessage()],400);
        }
    }


}
