<?php

namespace App\Http\Livewire\Admin;

use App\Models\Counteragent;
use App\Models\Order;
use App\Models\Product;
use App\Models\Report;
use App\Models\Store;
use App\Models\User;
use DB;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class ReportProduct extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $storeId;

    public $search;

    public $start_date;

    public $end_date;

    public function render()
    {
//          $products = Product::query()
////            ->join('receipt_products','receipt_products.product_id','products.id')
////            ->join('receipts','receipts.id','receipt_products.receipt_id')
////            ->where('receipts.store_id',$this->storeId)
////            ->whereDate('receipts.created_at','>=',$this->start_date)
////            ->whereDate('receipts.created_at','<=',$this->end_date)
//            ->select('products.*')
//            ->groupBy('products.id')
//            ->get();
//
//        $data = [];
//
//        foreach ($products as $product) {
//            $item = [];
//
//            $item['id'] = $product->id;
//            $item['product_name'] = $product->name;
//            $item['remains_start'] =(float)  $product->remainsDateTo($this->start_date);
//            $item['remains_end'] = (float)$product->remainsDateTo($this->end_date);
//            $item['receipt'] = (float)Product::query()
//                ->where('products.id',$product->id)
//                ->join('receipt_products','receipt_products.product_id','products.id')
//                ->join('receipts','receipts.id','receipt_products.receipt_id')
//                ->where('receipts.store_id',$this->storeId)
//                ->whereDate('receipts.created_at','>=',$this->start_date)
//                ->whereDate('receipts.created_at','<=',$this->end_date)
//                ->where('operation',1)
//                ->where('source',1)
//                ->sum('count') ?? 0;
//
//            $item['receipt_all'] = (float)Product::query()
//                ->where('products.id',$product->id)
//                ->join('receipt_products','receipt_products.product_id','products.id')
//                ->join('receipts','receipts.id','receipt_products.receipt_id')
//                ->where('receipts.store_id',$this->storeId)
//                ->whereDate('receipts.created_at','>=',$this->start_date)
//                ->whereDate('receipts.created_at','<=',$this->end_date)
//                ->sum('count') ?? 0;
//
//            $item['refund'] = (float)Product::query()
//                ->where('products.id',$product->id)
//                ->join('refund_products','refund_products.product_id','products.id')
//                ->join('refunds','refunds.id','refund_products.refund_id')
//                ->where('refunds.store_id',$this->storeId)
//                ->whereDate('refunds.created_at','>=',$this->start_date)
//                ->whereDate('refunds.created_at','<=',$this->end_date)
//                ->sum('count') ?? 0;
//
//
//            $item['refund_producer'] = (float)Product::query()
//                ->where('products.id',$product->id)
//                ->join('refund_producer_products','refund_producer_products.product_id','products.id')
//                ->join('refund_producers','refund_producers.id','refund_producer_products.refund_producer_id')
//                ->where('refund_producers.store_id',$this->storeId)
//                ->whereDate('refund_producers.created_at','>=',$this->start_date)
//                ->whereDate('refund_producers.created_at','<=',$this->end_date)
//                ->sum('count') ?? 0;
//
//            $item['order'] = (float)Product::query()
//                ->where('products.id',$product->id)
//                ->join('order_products','order_products.product_id','products.id')
//                ->join('orders','orders.id','order_products.order_id')
//                ->where('orders.store_id',$this->storeId)
//                ->whereDate('orders.created_at','>=',$this->start_date)
//                ->whereDate('orders.created_at','<=',$this->end_date)
//                ->sum('count') ?? 0;
//
//            $item['moving_from'] = (float) Product::query()
//                ->where('products.id',$product->id)
//                ->join('moving_products','moving_products.product_id','products.id')
//                ->join('movings','movings.id','moving_products.moving_id')
//                ->where('movings.store_id',$this->storeId)
//                ->where('movings.operation',1)
//                ->whereDate('movings.created_at','>=',$this->start_date)
//                ->whereDate('movings.created_at','<=',$this->end_date)
//                ->sum('count') ?? 0;
//            $item['moving_to'] = (float)Product::query()
//                ->where('products.id',$product->id)
//                ->join('moving_products','moving_products.product_id','products.id')
//                ->join('movings','movings.id','moving_products.moving_id')
//                ->where('movings.store_id',$this->storeId)
//                ->where('movings.operation',2)
//                ->whereDate('movings.created_at','>=',$this->start_date)
//                ->whereDate('movings.created_at','<=',$this->end_date)
//                ->sum('count') ?? 0;
//            $item['overage'] = (float)Product::query()
//                ->where('products.id',$product->id)
//                ->join('inventory_products','inventory_products.product_id','products.id')
//                ->join('inventories','inventories.id','inventory_products.inventory_id')
//                ->where('inventories.store_id',$this->storeId)
//                ->whereDate('inventories.created_at','>=',$this->start_date)
//                ->whereDate('inventories.created_at','<=',$this->end_date)
//                ->latest('inventories.created_at')
//                ->first()?->overage ?? 0;
//
//            $item['reject'] = (float) Product::query()
//                ->where('products.id',$product->id)
//                ->join('reject_products','reject_products.product_id','products.id')
//                ->join('rejects','rejects.id','reject_products.reject_id')
//                ->where('rejects.store_id',$this->storeId)
//                ->where('rejects.source',1)
//                ->whereDate('rejects.created_at','>=',$this->start_date)
//                ->whereDate('rejects.created_at','<=',$this->end_date)
//                ->sum('count') ?? 0;;
//
//            $item['reject_all'] =    (float) $item['reject'] + $item['refund_producer'] + $item['order'] + $item['moving_to'];
//
//            $data[] = $item;
//        }


        $data = Product::select([
            'products.id',
            'products.name',
            'products.id AS product_id',
            'id_1c',
            'measure',
            'article',
            DB::raw('COALESCE(moving_from.sum_count, 0) AS moving_from'),
            DB::raw('COALESCE(moving_to.sum_count, 0) AS moving_to'),
            DB::raw('COALESCE(receipt.sum_count, 0) AS receipt'),
            DB::raw('COALESCE(orderProduct.sum_count, 0) AS sale'),
            DB::raw('COALESCE(refund.sum_count, 0) AS refund'),
            DB::raw('COALESCE(refund_producer.sum_count, 0) AS refund_producer'),
            DB::raw('COALESCE(reject.sum_count, 0) AS reject'),
            DB::raw('COALESCE(receipt.sum_count, 0) + COALESCE(moving_from.sum_count, 0)  - COALESCE(orderProduct.sum_count, 0) - COALESCE(refund_producer.sum_count, 0) - COALESCE(moving_to.sum_count, 0)  - COALESCE(reject.sum_count, 0) AS remains')
        ])
            ->leftJoinSub(
                function ($query) {
                    $query->select(
                        'moving_products.product_id',
                        DB::raw('SUM(moving_products.count) AS sum_count')
                    )
                        ->from('moving_products')
                        ->join('movings', 'movings.id', '=', 'moving_products.moving_id')
                        ->where('movings.operation', 1)
                        ->where('movings.store_id', $this->storeId)
                        ->whereDate('movings.created_at','>=',$this->start_date)
                        ->whereDate('movings.created_at','<=',$this->end_date)
                        ->groupBy('moving_products.product_id');
                },
                'moving_from',
                'moving_from.product_id',
                '=',
                'products.id'
            )
            ->leftJoinSub(
                function ($query){
                    $query->select(
                        'moving_products.product_id',
                        DB::raw('SUM(moving_products.count) AS sum_count')
                    )
                        ->from('moving_products')
                        ->join('movings', 'movings.id', '=', 'moving_products.moving_id')
                        ->where('movings.operation', 2)
                        ->where('movings.store_id',  $this->storeId)
                        ->whereDate('movings.created_at','>=',$this->start_date)
                        ->whereDate('movings.created_at','<=',$this->end_date)
                        ->groupBy('moving_products.product_id');
                },
                'moving_to',
                'moving_to.product_id',
                '=',
                'products.id'
            )
            ->leftJoinSub(
                function ($query)  {
                    $query->select(
                        'reject_products.product_id',
                        DB::raw('SUM(reject_products.count) AS sum_count')
                    )
                        ->from('reject_products')
                        ->join('rejects', 'rejects.id', '=', 'reject_products.reject_id')
                        ->where('rejects.store_id',  $this->storeId)
                        ->whereDate('rejects.created_at','>=',$this->start_date)
                        ->whereDate('rejects.created_at','<=',$this->end_date)
                        ->groupBy('reject_products.product_id');
                },
                'reject',
                'reject.product_id',
                '=',
                'products.id'
            )
            ->leftJoinSub(
                function ($query)  {
                    $query->select(
                        'refund_products.product_id',
                        DB::raw('SUM(refund_products.count) AS sum_count')
                    )
                        ->from('refund_products')
                        ->join('refunds', 'refunds.id', '=', 'refund_products.refund_id')
                        ->where('refunds.store_id',  $this->storeId)
                        ->whereDate('refunds.created_at','>=',$this->start_date)
                        ->whereDate('refunds.created_at','<=',$this->end_date)
                        ->groupBy('refund_products.product_id');
                },
                'refund',
                'refund.product_id',
                '=',
                'products.id'
            )
            ->leftJoinSub(
                function ($query)  {
                    $query->select(
                        'refund_producer_products.product_id',
                        DB::raw('SUM(refund_producer_products.count) AS sum_count')
                    )
                        ->from('refund_producer_products')
                        ->join('refund_producers', 'refund_producers.id', '=', 'refund_producer_products.refund_producer_id')
                        ->where('refund_producers.store_id',  $this->storeId)
                        ->whereDate('refund_producers.created_at','>=',$this->start_date)
                        ->whereDate('refund_producers.created_at','<=',$this->end_date)
                        ->groupBy('refund_producer_products.product_id');
                },
                'refund_producer',
                'refund_producer.product_id',
                '=',
                'products.id'
            )
            ->leftJoinSub(
                function ($query)  {
                    $query->select(
                        'receipt_products.product_id',
                        DB::raw('SUM(receipt_products.count) AS sum_count')
                    )
                        ->from('receipt_products')
                        ->join('receipts', 'receipts.id', '=', 'receipt_products.receipt_id')
                        ->where('receipts.store_id',  $this->storeId)
                        ->whereDate('receipts.created_at','>=',$this->start_date)
                        ->whereDate('receipts.created_at','<=',$this->end_date)
                        ->groupBy('receipt_products.product_id');
                },
                'receipt',
                'receipt.product_id',
                '=',
                'products.id'
            )
            ->leftJoinSub(
                function ($query)  {
                    $query->select(
                        'order_products.product_id',
                        DB::raw('SUM(order_products.count) AS sum_count')
                    )
                        ->from('order_products')
                        ->join('orders', 'orders.id', '=', 'order_products.order_id')
                        ->where('orders.store_id',  $this->storeId)
                        ->whereNotNull('orders.check_number')
                        ->whereDate('orders.created_at','>=',$this->start_date)
                        ->whereDate('orders.created_at','<=',$this->end_date)
                        ->groupBy('order_products.product_id');
                },
                'orderProduct',
                'orderProduct.product_id',
                '=',
                'products.id'
            )
            ->groupBy('products.id')
            ->orderBy('products.name')
//            ->having('remains', '>', 0)
            ->get();

        return view('admin.report.product_live', [
            'reports' => $data,
        ]);
    }



    public function mount($storeId)
    {
        $this->storeId =$storeId;
//        $this->start_date = now()->format('Y-m-d');
        $this->start_date = now()->startOfYear()->format('Y-m-d');
        $this->end_date = now()->format('Y-m-d');
    }

}
