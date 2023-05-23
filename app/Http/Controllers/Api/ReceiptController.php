<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\AuthLoginRequest;
use App\Http\Requests\Api\OrderCheckRequest;
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
use App\Services\WebKassa\WebKassaService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReceiptController extends Controller
{
    public function index(ReceiptIndexRequest $request)
    {
        $receipts = Receipt::query()
            ->where('receipts.user_id',Auth::id())
            ->when($request->has('date_from'),function ($q){
                $q->whereDate('created_at','>=',request('date_from'));
            })
            ->when($request->has('date_to'),function ($q){
                $q->whereDate('created_at','<=',request('date_to'));
            })
            ->with(['products','products.product','store'])
            ->latest()
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
            ->with(['products','products.product','store'])
            ->latest()
            ->get();
        return response()->json($receipts);
    }

    public function store(ReceiptStoreRequest $request)
    {
        $data = [];
        $data['storage_id'] = Auth::user()->storage_id;
        $data['store_id'] = Auth::user()->store_id;
        $data['organization_id'] = Auth::user()->organization_id;

        $receipt = Auth::user()->receipts()->create(array_merge($request->validated(),$data));
        if ($request->has('products'))
        {
            foreach ($request->get('products') as $item) {
                $product = Product::find($item['product_id']);
                if (!$product) continue;
                $receiptProduct = ReceiptProduct::query()->join('receipts','receipts.id','receipt_products.receipt_id')
                    ->where('receipts.user_id',Auth::id())
                    ->where('receipt_products.product_id',$item['product_id'])
                    ->select('receipt_products.*')
                    ->latest()
                    ->first();
                if ($receiptProduct){
                    $item['old_price'] = $receiptProduct->price;
                }
                $item['all_price'] = $item['count'] * $item['price'];

                $receipt->products()->updateOrCreate([
                    'product_id' => $product->id,
                    'receipt_id' => $receipt->id
                ],$item);
            }
            $receipt->update([
                'product_history' => $receipt->products()->select('product_id','count','price','all_price','comment')->get()->toArray(),
                'total_price' => $receipt->products()->sum('all_price')
            ]);
        }



        return response()->json($receipt);

    }

    public function delete(Receipt $receipt)
    {
        $receipt->delete();
        return response()->json($receipt);
    }

    public function check(Receipt $receipt,OrderCheckRequest $request)
    {
        try {
            $data =  WebKassaService::checkReceipt($receipt,$request->get('payments'));
            return response()->json($data);
        }catch (\Exception $exception){
            return response()->json(['message' => $exception->getMessage()],400);
        }
    }
}
