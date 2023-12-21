<?php

namespace App\Http\Controllers\Api;

use App\Actions\ReceiptStoreAction;
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
use App\Models\WebkassaCheck;
use App\Services\WebKassa\WebKassaService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
//Поступление товара
class ReceiptController extends Controller
{
    public function index(ReceiptIndexRequest $request)
    {
        $receipts = Receipt::query()
            ->where('receipts.store_id',Auth::user()->store_id)
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
            ->where('receipts.store_id',Auth::user()->store_id)
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

    public function store(ReceiptStoreRequest $request,ReceiptStoreAction $receiptStoreAction)
    {
        $data = [];
        $data['storage_id'] = Auth::user()->storage_id;
        $data['store_id'] = Auth::user()->store_id;
        $data['organization_id'] = Auth::user()->organization_id;
        try {
            return response()->json($receiptStoreAction->execute(array_merge($request->validated(),$data)));
        }catch (\Exception $exception)
        {
            return response()->json(['message' => $exception->getMessage()],400);
        }
    }

    public function update(ReceiptUpdateRequest $request,Receipt $receipt)
    {

        try {

            foreach ($request->get('products') as $item) {
                $product = Product::find($item['product_id']);
                if (!$product) continue;

                $item['all_price'] = $item['count'] * $item['price'];
                $receipt->products()->updateOrCreate(['product_id' => $product->id,'receipt_id' => $receipt->id ],$item);
            }
            $receipt->update(['product_history' => $receipt->products()->select('product_id','count','price','all_price','comment')->get()->toArray(), 'total_price' => $receipt->products()->sum('all_price')]);

        }catch (\Exception $exception)
        {
            return response()->json(['message' => $exception->getMessage()],400);
        }
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

    public function printCheck(Receipt $receipt)
    {
        try {
            $check = WebkassaCheck::where('receipt_id',$receipt->id)->latest()->first();


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
}
