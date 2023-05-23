<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\RejectIndexRequest;
use App\Http\Requests\Api\RejectStoreRequest;
use App\Models\Order;
use App\Models\Receipt;
use App\Models\ReceiptProduct;
use App\Models\Reject;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
//Списание
class RejectController extends Controller
{
    public function index(RejectIndexRequest $request)
    {
        $rejects = Reject::query()
            ->where('rejects.user_id',Auth::id())
            ->with(['products','products.product','store'])
            ->get();
        return response()->json($rejects);
    }

    public function history(RejectIndexRequest $request)
    {
        $rejects = Reject::query()
            ->where('rejects.user_id',Auth::id())
            ->with(['products','products.product','store'])
            ->get();
        return response()->json($rejects);
    }

    public function store(RejectStoreRequest $request)
    {
        $data = [];
        $data['storage_id'] = Auth::user()->storage_id;
        $data['store_id'] = Auth::user()->store_id;
        $data['organization_id'] = Auth::user()->organization_id;

        $reject = Auth::user()->rejects()->create(array_merge($request->validated(),$data));
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
                    $reject->forceDelete();
                    return response()->json(['message' => "продукт $product->name не найден"],404);
                }

                $item['price'] = $receiptProduct->price;
                $item['all_price'] = $item['count'] * $item['price'];

                $reject->products()->updateOrCreate([
                    'product_id' => $product->id,
                    'reject_id' => $reject->id
                ],$item);
            }
            $reject->update([
                'product_history' => $reject->products()->select('product_id','count','price','all_price','comment')->get()->toArray(),
                'total_price' => $reject->products()->sum('all_price')
            ]);
        }



        return response()->json($reject);

    }

    public function delete(Reject $reject)
    {
        $reject->delete();
        return response()->json($reject);
    }


}
