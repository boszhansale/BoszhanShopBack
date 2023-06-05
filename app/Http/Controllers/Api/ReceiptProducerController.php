<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\AuthLoginRequest;
use App\Http\Requests\Api\ReceiptIndexRequest;
use App\Http\Requests\Api\ReceiptStoreRequest;
use App\Http\Requests\Api\ReceiptUpdateRequest;
use App\Http\Requests\Api\UserUpdateRequest;
use App\Http\Resources\UserResource;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Receipt;
use App\Models\ReceiptProduct;
use App\Models\PriceType;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReceiptProducerController extends Controller
{
    public function index(ReceiptIndexRequest $request)
    {
        $receipts = Receipt::query()
            ->where('receipts.user_id',Auth::id())
            ->with(['products','products.product'])
            ->when($request->has('date_from'),function ($q){
                $q->whereDate('created_at','>=',request('date_from'));
            })
            ->when($request->has('date_to'),function ($q){
                $q->whereDate('created_at','<=',request('date_to'));
            })
            ->get();
        return response()->json($receipts);
    }

    public function history(ReceiptIndexRequest $request)
    {
        $receipts = Receipt::query()
            ->where('receipts.user_id',Auth::id())
            ->when($request->has('date_from'),function ($q){
                $q->whereDate('created_at','>=',request('date_from'));
            })
            ->when($request->has('date_to'),function ($q){
                $q->whereDate('created_at','<=',request('date_to'));
            })
            ->with(['products','products.product'])
            ->get();
        return response()->json($receipts);
    }

    public function store(ReceiptStoreRequest $request)
    {
        $data = [];
        $data['storage_id'] = Auth::user()->storage_id;
        $data['store_id'] = Auth::user()->store_id;
        $data['organization_id'] = Auth::user()->organization_id;

        $refund = Auth::user()->receipts()->create(array_merge($request->validated(),$data));
        $order = Order::findOrFail($request->get('order_id'));
        if ($request->has('products'))
        {
            foreach ($request->get('products') as $item) {
                $product = Product::find($item['product_id']);
                if (!$product) continue;
                $orderProduct = $order->products()->where('product_id',$item['product_id'])->latest()->first();
                if (!$orderProduct) continue;
                $item['price'] = $orderProduct->price;
                $item['all_price'] = $item['count'] * $item['price'];

                $refund->products()->updateOrCreate([
                    'product_id' => $product->id,
                    'refund_id' => $refund->id
                ],$item);
            }
            $refund->update([
                'product_history' => $refund->products()->select('product_id','count','price','all_price','comment')->get()->toArray(),
                'total_price' => $refund->products()->sum('all_price')
            ]);
        }



        return response()->json($refund);

    }

    public function delete(Receipt $refund)
    {
        $refund->delete();
        return response()->json($refund);
    }


}
