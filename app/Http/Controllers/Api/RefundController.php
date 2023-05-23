<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\AuthLoginRequest;
use App\Http\Requests\Api\OrderCheckRequest;
use App\Http\Requests\Api\RefundIndexRequest;
use App\Http\Requests\Api\RefundStoreRequest;
use App\Http\Requests\Api\RefundUpdateRequest;
use App\Http\Requests\Api\UserUpdateRequest;
use App\Http\Resources\UserResource;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Refund;
use App\Models\RefundProduct;
use App\Models\PriceType;
use App\Models\Product;
use App\Models\User;
use App\Services\WebKassa\WebKassaService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RefundController extends Controller
{
    public function index(RefundIndexRequest $request)
    {
        $refunds = Refund::query()
            ->where('refunds.user_id',Auth::id())
            ->with(['products','products.product','products.reasonRefund','store'])
            ->latest()
            ->get();
        return response()->json($refunds);
    }

    public function history(RefundIndexRequest $request)
    {
        $refunds = Refund::query()
            ->where('refunds.user_id',Auth::id())
            ->with(['products','products.product','products.reasonRefund','store'])
            ->latest()

            ->get();
        return response()->json($refunds);
    }

    public function store(RefundStoreRequest $request)
    {
        $data = [];
        $data['storage_id'] = Auth::user()->storage_id;
        $data['store_id'] = Auth::user()->store_id;
        $data['organization_id'] = Auth::user()->organization_id;

        $refund = Auth::user()->refunds()->create(array_merge($request->validated(),$data));
        $order = Order::findOrFail($request->get('order_id'));
        if ($request->has('products'))
        {
            foreach ($request->get('products') as $item) {
                $product = Product::find($item['product_id']);
                if (!$product) continue;
                $orderProduct = $order->products()->where('product_id',$item['product_id'])->latest()->first();
                if (!$orderProduct){
                    $refund->forceDelete();

                    return  response()->json(['message' => "продукт $product->name не найден"],404);
                };
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

    public function delete(Refund $refund)
    {
        $refund->delete();
        return response()->json($refund);
    }

    public function check(Refund $refund,OrderCheckRequest $request)
    {
        try {
            $data =  WebKassaService::checkRefund($refund,$request->get('payments'));
            return response()->json($data);
        }catch (\Exception $exception){
            return response()->json(['message' => $exception->getMessage()],400);
        }
    }


}
