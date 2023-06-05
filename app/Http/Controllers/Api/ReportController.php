<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ReportDiscountCardRequest;
use App\Http\Requests\Api\ReportProductRequest;
use App\Http\Requests\Api\ReportRemainsRequest;
use App\Models\DiscountCard;
use App\Models\Inventory;
use App\Models\Order;
use App\Models\Product;
use DB;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    //остатки
    public function remains(ReportRemainsRequest $request)
    {
        $dateFrom = now()->subYear()->format('Y-m-d'); // Default date_from value
        $dateTo = now()->format('Y-m-d'); // Default date_to value

        if ($request->has('date_from')) {
            $dateFrom = $request->input('date_from');
        }

        if ($request->has('date_to')) {
            $dateTo = $request->input('date_to');
        }

        $result = DB::table('products')
            ->select('products.name', 'products.id AS product_id','id_1c','measure','article',
                DB::raw('COALESCE(moving.sum_count, 0) AS moving'),
                DB::raw('COALESCE(receipt.sum_count, 0) AS receipt'),
                DB::raw('COALESCE(orderProduct.sum_count, 0) AS sale'),
                DB::raw('COALESCE(receipt.sum_count, 0) - COALESCE(orderProduct.sum_count, 0) - COALESCE(moving.sum_count, 0) AS remains')
            )
            ->leftJoinSub(
                DB::table('moving_products')
                    ->select('moving_products.product_id', DB::raw('SUM(moving_products.count) AS sum_count'))
                    ->join('movings', 'movings.id', '=', 'moving_products.moving_id')
                    ->where('movings.operation', 1)
                    ->whereBetween('movings.created_at', [$dateFrom, $dateTo])
                    ->groupBy('moving_products.product_id'),
                'moving', 'moving.product_id', '=', 'products.id'
            )
            ->leftJoinSub(
                DB::table('receipt_products')
                    ->select('receipt_products.product_id', DB::raw('SUM(receipt_products.count) AS sum_count'))
                    ->whereBetween('receipt_products.created_at', [$dateFrom, $dateTo])
                    ->groupBy('receipt_products.product_id'),
                'receipt', 'receipt.product_id', '=', 'products.id'
            )
            ->leftJoinSub(
                DB::table('order_products')
                    ->select('order_products.product_id', DB::raw('SUM(order_products.count) AS sum_count'))
                    ->whereBetween('order_products.created_at', [$dateFrom, $dateTo])
                    ->groupBy('order_products.product_id'),
                'orderProduct', 'orderProduct.product_id', '=', 'products.id'
            )
            ->groupBy('products.id')
            ->having('remains', '>', 0)
            ->get();




        return response()->json($result);
    }

    public function discountCard(ReportDiscountCardRequest $request)
    {
        $result = Order::query()
            ->where('user_id',Auth::id())
            ->whereNotNull('discount_phone')
            ->when($request->has('date_from'),function ($q){
                $q->whereDate('created_at','>=',request('date_from'));
            })
            ->when($request->has('date_to'),function ($q){
                $q->whereDate('created_at','<=',request('date_to'));
            })
            ->when($request->has('search'),function ($q){
                $q->where('discount_phone','LIKE','%'.\request('search').'%');
            })
            ->with(['products.product'])
            ->latest()
            ->get();


        return response()->json($result);
    }

    public function order(ReportDiscountCardRequest $request)
    {
        $result = Order::query()
            ->where('user_id',Auth::id())
            ->when($request->has('date_from'),function ($q){
                $q->whereDate('created_at','>=',request('date_from'));
            })
            ->when($request->has('date_to'),function ($q){
                $q->whereDate('created_at','<=',request('date_to'));
            })
            ->when($request->has('search'),function ($q){
                $q->where('id','LIKE','%'.\request('search').'%');
            })
            ->with(['products.product'])
            ->latest()
            ->get();


        return response()->json($result);
    }

    public function inventor(ReportDiscountCardRequest $request)
    {
        $inventories = Inventory::query()
            ->where('user_id',Auth::id())
            ->with(['products','products.product'])
            ->when($request->has('date_from'),function ($q){
                $q->whereDate('created_at','>=',request('date_from'));
            })
            ->when($request->has('date_to'),function ($q){
                $q->whereDate('created_at','<=',request('date_to'));
            })
            ->latest()
            ->get();
        return response()->json($inventories);
    }

    public function product(ReportProductRequest $request)
    {

//            Остаток на начало = remains_start
//            Поступило = receipt_all
//            Возврат от покупателя = refund
//            Поступление от поставщика = receipt
//            Излишки = overage
//            Перемещение со склада = moving_from
//            Списано общее = reject
//            Списание = reject_all
//            Возврат поставщику = refund_producer
//            Продажи = order
//            Перемещение на склад = moving_to
//            Остатки на конец = remains_end
        $products = Product::query()
            ->join('receipt_products','receipt_products.product_id','products.id')
            ->join('receipts','receipts.id','receipt_products.receipt_id')
            ->where('receipts.user_id',Auth::id())
            ->whereDate('receipts.created_at','>=',$request->get('date_from'))
            ->whereDate('receipts.created_at','<=',$request->get('date_to'))
            ->select('products.*')
            ->groupBy('products.id')
            ->get();
        $data = [];
        foreach ($products as $product) {
            $item = [];

            $item['product_name'] = $product->name;
            $item['remains_start'] =(float)  $product->remainsDateTo($request->get('date_from'));
            $item['remains_end'] = (float)$product->remainsDateTo($request->get('date_to'));
            $item['receipt'] = (float)Product::query()
                ->where('products.id',$product->id)
                ->join('receipt_products','receipt_products.product_id','products.id')
                ->join('receipts','receipts.id','receipt_products.receipt_id')
                ->where('receipts.user_id',Auth::id())
                ->whereDate('receipts.created_at','>=',$request->get('date_from'))
                ->whereDate('receipts.created_at','<=',$request->get('date_to'))
                ->where('operation',1)
                ->where('source',1)
                ->sum('count') ?? 0;

            $item['receipt_all'] = (float)Product::query()
                ->where('products.id',$product->id)
                ->join('receipt_products','receipt_products.product_id','products.id')
                ->join('receipts','receipts.id','receipt_products.receipt_id')
                ->where('receipts.user_id',Auth::id())
                ->whereDate('receipts.created_at','>=',$request->get('date_from'))
                ->whereDate('receipts.created_at','<=',$request->get('date_to'))
                ->sum('count') ?? 0;

            $item['refund'] = (float)Product::query()
                ->where('products.id',$product->id)
                ->join('refund_products','refund_products.product_id','products.id')
                ->join('refunds','refunds.id','refund_products.refund_id')
                ->where('refunds.user_id',Auth::id())
                ->whereDate('refunds.created_at','>=',$request->get('date_from'))
                ->whereDate('refunds.created_at','<=',$request->get('date_to'))
                ->sum('count') ?? 0;


            $item['refund_producer'] = (float)Product::query()
                ->where('products.id',$product->id)
                ->join('refund_producer_products','refund_producer_products.product_id','products.id')
                ->join('refund_producers','refund_producers.id','refund_producer_products.refund_producer_id')
                ->where('refund_producers.user_id',Auth::id())
                ->whereDate('refund_producers.created_at','>=',$request->get('date_from'))
                ->whereDate('refund_producers.created_at','<=',$request->get('date_to'))
                ->sum('count') ?? 0;

            $item['order'] = (float)Product::query()
                ->where('products.id',$product->id)
                ->join('order_products','order_products.product_id','products.id')
                ->join('orders','orders.id','order_products.order_id')
                ->where('orders.user_id',Auth::id())
                ->whereDate('orders.created_at','>=',$request->get('date_from'))
                ->whereDate('orders.created_at','<=',$request->get('date_to'))
                ->sum('count') ?? 0;

            $item['moving_from'] =(float) Product::query()
                ->where('products.id',$product->id)
                ->join('moving_products','moving_products.product_id','products.id')
                ->join('movings','movings.id','moving_products.moving_id')
                ->where('movings.user_id',Auth::id())
                ->where('movings.operation',1)
                ->whereDate('movings.created_at','>=',$request->get('date_from'))
                ->whereDate('movings.created_at','<=',$request->get('date_to'))
                ->sum('count') ?? 0;
            $item['moving_to'] = (float)Product::query()
                ->where('products.id',$product->id)
                ->join('moving_products','moving_products.product_id','products.id')
                ->join('movings','movings.id','moving_products.moving_id')
                ->where('movings.user_id',Auth::id())
                ->where('movings.operation',2)
                ->whereDate('movings.created_at','>=',$request->get('date_from'))
                ->whereDate('movings.created_at','<=',$request->get('date_to'))
                ->sum('count') ?? 0;
            $item['overage'] = (float)Product::query()
                ->where('products.id',$product->id)
                ->join('inventory_products','inventory_products.product_id','products.id')
                ->join('inventories','inventories.id','inventory_products.inventory_id')
                ->where('inventories.user_id',Auth::id())
                ->whereDate('inventories.created_at','>=',$request->get('date_from'))
                ->whereDate('inventories.created_at','<=',$request->get('date_to'))
                ->latest('inventories.created_at')
                ->first()?->overage ?? 0;

            $item['reject'] = (float) Product::query()
                ->where('products.id',$product->id)
                ->join('reject_products','reject_products.product_id','products.id')
                ->join('rejects','rejects.id','reject_products.reject_id')
                ->where('rejects.user_id',Auth::id())
                ->where('rejects.source',1)
                ->whereDate('rejects.created_at','>=',$request->get('date_from'))
                ->whereDate('rejects.created_at','<=',$request->get('date_to'))
                ->sum('count') ?? 0;;

            $item['reject_all'] =    (float) $item['reject'] + $item['refund_producer'] + $item['order'] + $item['moving_to'];

            $data[] = $item;
        }

        return response()->json($data);
    }
}
