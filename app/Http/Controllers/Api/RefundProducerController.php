<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\AuthLoginRequest;
use App\Http\Requests\Api\OrderCheckRequest;
use App\Http\Requests\Api\RefundProducerIndexRequest;
use App\Http\Requests\Api\RefundProducerStoreRequest;
use App\Http\Requests\Api\UserUpdateRequest;
use App\Http\Resources\UserResource;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\ReceiptProduct;
use App\Models\RefundProducer;
use App\Models\RefundProducerProduct;
use App\Models\PriceType;
use App\Models\Product;
use App\Models\User;
use App\Models\WebkassaCheck;
use App\Services\WebKassa\WebKassaService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RefundProducerController extends Controller
{
    public function index(RefundProducerIndexRequest $request)
    {
        $refundProducers = RefundProducer::query()
            ->where('refund_producers.user_id',Auth::id())
            ->with(['products','products.product','store'])
            ->when($request->has('date_from'),function ($q){
                $q->whereDate('created_at','>=',request('date_from'));
            })
            ->when($request->has('date_to'),function ($q){
                $q->whereDate('created_at','<=',request('date_to'));
            })
            ->get();
        return response()->json($refundProducers);
    }

    public function history(RefundProducerIndexRequest $request)
    {
        $refundProducers = RefundProducer::query()
            ->where('refund_producers.user_id',Auth::id())
            ->with(['products','products.product','store'])
            ->when($request->has('date_from'),function ($q){
                $q->whereDate('created_at','>=',request('date_from'));
            })
            ->when($request->has('date_to'),function ($q){
                $q->whereDate('created_at','<=',request('date_to'));
            })
            ->get();
        return response()->json($refundProducers);
    }

    public function store(RefundProducerStoreRequest $request)
    {
        $data = [];
        $data['storage_id'] = Auth::user()->storage_id;
        $data['store_id'] = Auth::user()->store_id;
        $data['organization_id'] = Auth::user()->organization_id;

        $refundProducer = Auth::user()->refundProducer()->create(array_merge($request->validated(),$data));
        if ($request->has('products'))
        {
            foreach ($request->get('products') as $item) {
                $product = Product::find($item['product_id']);
                if (!$product) continue;

                $receiptProduct = ReceiptProduct::query()
                    ->join('receipts','receipts.id','receipt_products.receipt_id')
                    ->where('receipts.user_id',Auth::id())
                    ->where('product_id',$product->id)
                    ->latest()
                    ->select('receipt_products.*')
                    ->first();

                if (!$receiptProduct){
                    $refundProducer->forceDelete();
                    return response()->json(['message' => "продукт $product->name не найден"],404);
                }
                $item['price'] = $receiptProduct->price;
                $item['all_price'] = $item['count'] * $item['price'];

                $refundProducer->products()->updateOrCreate([
                    'product_id' => $product->id,
                    'refund_producer_id' => $refundProducer->id
                ],$item);
            }
            $refundProducer->update([
                'product_history' => $refundProducer->products()->select('product_id','count','price','all_price','comment')->get()->toArray(),
                'total_price' => $refundProducer->products()->sum('all_price')
            ]);
        }



        return response()->json($refundProducer);

    }

    public function delete(RefundProducer $refundProducer)
    {
        $refundProducer->delete();
        return response()->json($refundProducer);
    }

    public function check(RefundProducer $refundProducer,OrderCheckRequest $request)
    {
        try {
            $data =  WebKassaService::checkRefundProducer($refundProducer,$request->get('payments'));
            return response()->json($data);
        }catch (\Exception $exception){
            return response()->json(['message' => $exception->getMessage()],400);
        }
    }

    public function printCheck(RefundProducer $refundProducer)
    {
        try {
            $check = WebkassaCheck::where('refund_producer_id',$refundProducer->id)->latest()->first();


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
