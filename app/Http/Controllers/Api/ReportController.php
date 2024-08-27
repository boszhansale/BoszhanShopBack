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
use App\Models\Report;
use DB;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    //остатки
    public function remains(ReportRemainsRequest $request)
    {
//        $dateFrom = now()->subYear()->format('Y-m-d'); // Default date_from value
//        $dateTo = now()->format('Y-m-d'); // Default date_to value
//
//        if ($request->has('date_from')) {
//            $dateFrom = $request->input('date_from');
//        }
//
//        if ($request->has('date_to')) {
//            $dateTo = $request->input('date_to');
//        }

//        $dateFrom = $request->get('date_from');
        $dateTo = $request->get('date_to');
//        $dateTo = null;


        $storeId = Auth::user()->store_id;

        $result = DB::table('products')
            ->select('products.name', 'products.id AS product_id', 'id_1c', 'measure', 'article',
                DB::raw('COALESCE(moving_from.sum_count, 0) AS moving_from'),
                DB::raw('COALESCE(moving_to.sum_count, 0) AS moving_to'),
                DB::raw('COALESCE(receipt.sum_count, 0) AS receipt'),
                DB::raw('COALESCE(orderProduct.sum_count, 0) AS sale'),
//                DB::raw('COALESCE(refund.sum_count, 0) AS refund'),
                DB::raw('COALESCE(refund_producer.sum_count, 0) AS refund_producer'),
                DB::raw('COALESCE(reject.sum_count, 0) AS reject'),
//                DB::raw('COALESCE(receipt.sum_count, 0) + COALESCE(moving_from.sum_count, 0) + COALESCE(refund.sum_count, 0) - COALESCE(orderProduct.sum_count, 0) - COALESCE(refund_producer.sum_count, 0) - COALESCE(moving_to.sum_count, 0)  - COALESCE(reject.sum_count, 0) AS remains')
                DB::raw('COALESCE(receipt.sum_count, 0) + COALESCE(moving_from.sum_count, 0) - COALESCE(orderProduct.sum_count, 0) - COALESCE(refund_producer.sum_count, 0) - COALESCE(moving_to.sum_count, 0)  - COALESCE(reject.sum_count, 0) AS remains')
            )
            ->leftJoin(
                DB::raw("(SELECT moving_products.product_id, SUM(moving_products.count) AS sum_count
            FROM moving_products
            INNER JOIN movings ON movings.id = moving_products.moving_id
            WHERE movings.operation = 1
            AND movings.store_id = $storeId
            " . ($dateTo ? "AND DATE(movings.created_at) <= '$dateTo' " : "") .
                    "GROUP BY moving_products.product_id) AS moving_from"),
                'moving_from.product_id', '=', 'products.id'
            )
            ->leftJoin(
                DB::raw("(SELECT moving_products.product_id, SUM(moving_products.count) AS sum_count
            FROM moving_products
            INNER JOIN movings ON movings.id = moving_products.moving_id
            WHERE movings.operation = 2
            AND movings.store_id = $storeId
            " . ($dateTo ? "AND DATE(movings.created_at) <= '$dateTo' " : "") .
                    "GROUP BY moving_products.product_id) AS moving_to"),
                'moving_to.product_id', '=', 'products.id'
            )
            ->leftJoin(
                DB::raw("(SELECT refund_products.product_id, SUM(refund_products.count) AS sum_count
                FROM refund_products
                INNER JOIN refunds ON refunds.id = refund_products.refund_id
                WHERE refunds.store_id = $storeId
            " . ($dateTo ? "AND refunds.created_at <= '$dateTo' " : "") .
                    "GROUP BY refund_products.product_id) AS refund"),
                'refund.product_id', '=', 'products.id'
            )
            ->leftJoin(
                DB::raw("(SELECT reject_products.product_id, SUM(reject_products.count) AS sum_count
            FROM reject_products
            INNER JOIN rejects ON rejects.id = reject_products.reject_id
            WHERE rejects.store_id = $storeId
            " . ($dateTo ? "AND DATE(rejects.created_at) <= '$dateTo' " : "") .
                    "GROUP BY reject_products.product_id) AS reject"),
                'reject.product_id', '=', 'products.id'
            )
            ->leftJoin(
                DB::raw("(SELECT refund_producer_products.product_id, SUM(refund_producer_products.count) AS sum_count
            FROM refund_producer_products
            INNER JOIN refund_producers ON refund_producers.id = refund_producer_products.refund_producer_id
            WHERE refund_producers.store_id = $storeId
            " . ($dateTo ? "AND DATE(refund_producers.created_at) <= '$dateTo' " : "") .
                    "GROUP BY refund_producer_products.product_id) AS refund_producer"),
                'refund_producer.product_id', '=', 'products.id'
            )
            ->leftJoin(
                DB::raw("(SELECT receipt_products.product_id, SUM(receipt_products.count) AS sum_count
        FROM receipt_products
        JOIN receipts ON receipts.id = receipt_products.receipt_id
        WHERE receipts.store_id = $storeId
        " . ($dateTo ? "AND DATE(receipts.created_at) <= '$dateTo' " : "") .
                    "GROUP BY receipt_products.product_id) AS receipt"),
                'receipt.product_id', '=', 'products.id'
            )
            ->leftJoin(
                DB::raw("(SELECT order_products.product_id, SUM(order_products.count) AS sum_count
        FROM order_products
        JOIN orders ON orders.id  = order_products.order_id
        WHERE orders.store_id = $storeId
        AND orders.check_number IS NOT NULL

        " . ($dateTo ? "AND DATE(orders.created_at) <= '$dateTo' " : "") .
                    "GROUP BY order_products.product_id) AS orderProduct"),
                'orderProduct.product_id', '=', 'products.id'
            )
            ->orderBy('products.name')
            ->groupBy('products.id')
            ->having('remains', '>', 0)
            ->get();
        return response()->json($result);
    }

    public function remainsPrint(ReportRemainsRequest $request)
    {
//        $dateFrom = now()->subYear()->format('Y-m-d'); // Default date_from value
//        $dateTo = now()->format('Y-m-d'); // Default date_to value
//
//        if ($request->has('date_from')) {
//            $dateFrom = $request->input('date_from');
//        }
//
//        if ($request->has('date_to')) {
//            $dateTo = $request->input('date_to');
//        }

//        $dateFrom = $request->get('date_from');
        $dateTo = $request->get('date_to');
//        $dateTo = null;


        $storeId = $request->get('store_id');

        $result = DB::table('products')
            ->select('products.name', 'products.id AS product_id', 'id_1c', 'measure', 'article',
                DB::raw('COALESCE(moving_from.sum_count, 0) AS moving_from'),
                DB::raw('COALESCE(moving_to.sum_count, 0) AS moving_to'),
                DB::raw('COALESCE(receipt.sum_count, 0) AS receipt'),
                DB::raw('COALESCE(orderProduct.sum_count, 0) AS sale'),
//                DB::raw('COALESCE(refund.sum_count, 0) AS refund'),
                DB::raw('COALESCE(refund_producer.sum_count, 0) AS refund_producer'),
                DB::raw('COALESCE(reject.sum_count, 0) AS reject'),
//                DB::raw('COALESCE(receipt.sum_count, 0) + COALESCE(moving_from.sum_count, 0) + COALESCE(refund.sum_count, 0) - COALESCE(orderProduct.sum_count, 0) - COALESCE(refund_producer.sum_count, 0) - COALESCE(moving_to.sum_count, 0)  - COALESCE(reject.sum_count, 0) AS remains')
                DB::raw('COALESCE(receipt.sum_count, 0) + COALESCE(moving_from.sum_count, 0)  - COALESCE(orderProduct.sum_count, 0) - COALESCE(refund_producer.sum_count, 0) - COALESCE(moving_to.sum_count, 0)  - COALESCE(reject.sum_count, 0) AS remains')
            )
            ->leftJoin(
                DB::raw("(SELECT moving_products.product_id, SUM(moving_products.count) AS sum_count
            FROM moving_products
            INNER JOIN movings ON movings.id = moving_products.moving_id
            WHERE movings.operation = 1
            AND movings.store_id = $storeId
            " . ($dateTo ? "AND DATE(movings.created_at) <= '$dateTo' " : "") .
                    "GROUP BY moving_products.product_id) AS moving_from"),
                'moving_from.product_id', '=', 'products.id'
            )
            ->leftJoin(
                DB::raw("(SELECT moving_products.product_id, SUM(moving_products.count) AS sum_count
            FROM moving_products
            INNER JOIN movings ON movings.id = moving_products.moving_id
            WHERE movings.operation = 2
            AND movings.store_id = $storeId
            " . ($dateTo ? "AND DATE(movings.created_at) <= '$dateTo' " : "") .
                    "GROUP BY moving_products.product_id) AS moving_to"),
                'moving_to.product_id', '=', 'products.id'
            )
//            ->leftJoin(
//                DB::raw("(SELECT refund_products.product_id, SUM(refund_products.count) AS sum_count
//                FROM refund_products
//                INNER JOIN refunds ON refunds.id = refund_products.refund_id
//                WHERE refunds.store_id = $storeId
//            " . ($dateTo ? "AND refunds.created_at <= '$dateTo' " : "") .
//                    "GROUP BY refund_products.product_id) AS refund"),
//                'refund.product_id', '=', 'products.id'
//            )
            ->leftJoin(
                DB::raw("(SELECT reject_products.product_id, SUM(reject_products.count) AS sum_count
            FROM reject_products
            INNER JOIN rejects ON rejects.id = reject_products.reject_id
            WHERE rejects.store_id = $storeId
            " . ($dateTo ? "AND DATE(rejects.created_at) <= '$dateTo' " : "") .
                    "GROUP BY reject_products.product_id) AS reject"),
                'reject.product_id', '=', 'products.id'
            )
            ->leftJoin(
                DB::raw("(SELECT refund_producer_products.product_id, SUM(refund_producer_products.count) AS sum_count
            FROM refund_producer_products
            INNER JOIN refund_producers ON refund_producers.id = refund_producer_products.refund_producer_id
            WHERE refund_producers.store_id = $storeId
            " . ($dateTo ? "AND DATE(refund_producers.created_at) <= '$dateTo' " : "") .
                    "GROUP BY refund_producer_products.product_id) AS refund_producer"),
                'refund_producer.product_id', '=', 'products.id'
            )
            ->leftJoin(
                DB::raw("(SELECT receipt_products.product_id, SUM(receipt_products.count) AS sum_count
        FROM receipt_products
        JOIN receipts ON receipts.id = receipt_products.receipt_id
        WHERE receipts.store_id = $storeId
        " . ($dateTo ? "AND DATE(receipts.created_at) <= '$dateTo' " : "") .
                    "GROUP BY receipt_products.product_id) AS receipt"),
                'receipt.product_id', '=', 'products.id'
            )
            ->leftJoin(
                DB::raw("(SELECT order_products.product_id, SUM(order_products.count) AS sum_count
        FROM order_products
        JOIN orders ON orders.id  = order_products.order_id
        WHERE orders.store_id = $storeId
        AND orders.check_number IS NOT NULL

        " . ($dateTo ? "AND DATE(orders.created_at) <= '$dateTo' " : "") .
                    "GROUP BY order_products.product_id) AS orderProduct"),
                'orderProduct.product_id', '=', 'products.id'
            )
            ->orderBy('products.name')
            ->groupBy('products.id')
            ->having('remains', '>', 0)
            ->get();
        return view('pdf.remains', ['data' => $result]);
    }

    public function discountCard(ReportDiscountCardRequest $request)
    {
        $result = Order::query()
            ->where('user_id', Auth::id())
            ->whereNotNull('discount_phone')
            ->when($request->has('date_from'), function ($q) {
                $q->whereDate('created_at', '>=', request('date_from'));
            })
            ->when($request->has('date_to'), function ($q) {
                $q->whereDate('created_at', '<=', request('date_to'));
            })
            ->when($request->has('search'), function ($q) {
                $q->where('discount_phone', 'LIKE', '%' . \request('search') . '%');
            })
            ->with(['products.product'])
            ->latest()
            ->get();


        return response()->json($result);
    }

    public function order(ReportDiscountCardRequest $request)
    {
        $result = Order::query()
            ->where('user_id', Auth::id())
            ->when($request->has('date_from'), function ($q) {
                $q->whereDate('created_at', '>=', request('date_from'));
            })
            ->when($request->has('date_to'), function ($q) {
                $q->whereDate('created_at', '<=', request('date_to'));
            })
            ->when($request->has('search'), function ($q) {
                $q->where('id', 'LIKE', '%' . \request('search') . '%');
            })
            ->with(['products.product'])
            ->latest()
            ->get();


        return response()->json($result);
    }

    public function inventor(ReportDiscountCardRequest $request)
    {
        $inventories = Inventory::query()
            ->where('user_id', Auth::id())
            ->with(['products', 'products.product'])
            ->when($request->has('date_from'), function ($q) {
                $q->whereDate('created_at', '>=', request('date_from'));
            })
            ->when($request->has('date_to'), function ($q) {
                $q->whereDate('created_at', '<=', request('date_to'));
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
            ->join('receipt_products', 'receipt_products.product_id', 'products.id')
            ->join('receipts', 'receipts.id', 'receipt_products.receipt_id')
            ->where('receipts.store_id', Auth::user()->store_id)
            ->where(function ($qq) {
                $qq->whereDate('receipts.created_at', '<', request('date'))
                    ->orWhere(function ($q) {
                        $q->whereDate('receipts.created_at', request('date'))->whereTime('receipts.created_at', '<', request('time'));
                    });
            })
            ->select('products.*')
            ->groupBy('products.id')
            ->get();
        $data = [];
        foreach ($products as $product) {
            $item = [];

            $item['product_name'] = $product->name;
            $item['remains_start'] = (float)$product->remainsDateFrom($request->get('date'));
            $item['remains_end'] = (float)$product->remainsDateTo($request->get('date'));
            $item['receipt'] = (float)Product::query()
                ->where('products.id', $product->id)
                ->join('receipt_products', 'receipt_products.product_id', 'products.id')
                ->join('receipts', 'receipts.id', 'receipt_products.receipt_id')
                ->where('receipts.store_id', Auth::user()->store_id)
                ->where(function ($qq) {
                    $qq->whereDate('receipts.created_at', '<', request('date'))
                        ->orWhere(function ($q) {
                            $q->whereDate('receipts.created_at', request('date'))->whereTime('receipts.created_at', '<', request('time'));
                        });
                })
                ->where('operation', 1)
                ->where('source', 1)
                ->sum('count') ?? 0;

            $item['receipt_all'] = (float)Product::query()
                ->where('products.id', $product->id)
                ->join('receipt_products', 'receipt_products.product_id', 'products.id')
                ->join('receipts', 'receipts.id', 'receipt_products.receipt_id')
                ->where('receipts.store_id', Auth::user()->store_id)
                ->where(function ($qq) {
                    $qq->whereDate('receipts.created_at', '<', request('date'))
                        ->orWhere(function ($q) {
                            $q->whereDate('receipts.created_at', request('date'))->whereTime('receipts.created_at', '<', request('time'));
                        });
                })
                ->sum('count') ?? 0;

            $item['refund'] = (float)Product::query()
                ->where('products.id', $product->id)
                ->join('refund_products', 'refund_products.product_id', 'products.id')
                ->join('refunds', 'refunds.id', 'refund_products.refund_id')
                ->where('refunds.store_id', Auth::user()->store_id)
                ->where(function ($qq) {
                    $qq->whereDate('refunds.created_at', '<', request('date'))
                        ->orWhere(function ($q) {
                            $q->whereDate('refunds.created_at', request('date'))->whereTime('refunds.created_at', '<', request('time'));
                        });
                })
                ->sum('count') ?? 0;


            $item['refund_producer'] = (float)Product::query()
                ->where('products.id', $product->id)
                ->join('refund_producer_products', 'refund_producer_products.product_id', 'products.id')
                ->join('refund_producers', 'refund_producers.id', 'refund_producer_products.refund_producer_id')
                ->where('refund_producers.store_id', Auth::user()->store_id)
                ->where(function ($qq) {
                    $qq->whereDate('refund_producers.created_at', '<', request('date'))
                        ->orWhere(function ($q) {
                            $q->whereDate('refund_producers.created_at', request('date'))->whereTime('refund_producers.created_at', '<', request('time'));
                        });
                })
                ->sum('count') ?? 0;

            $item['order'] = (float)Product::query()
                ->where('products.id', $product->id)
                ->join('order_products', 'order_products.product_id', 'products.id')
                ->join('orders', 'orders.id', 'order_products.order_id')
                ->where('orders.store_id', Auth::user()->store_id)
                ->where(function ($qq) {
                    $qq->whereDate('orders.created_at', '<', request('date'))
                        ->orWhere(function ($q) {
                            $q->whereDate('orders.created_at', request('date'))->whereTime('orders.created_at', '<', request('time'));
                        });
                })
                ->sum('count') ?? 0;

            $item['moving_from'] = (float)Product::query()
                ->where('products.id', $product->id)
                ->join('moving_products', 'moving_products.product_id', 'products.id')
                ->join('movings', 'movings.id', 'moving_products.moving_id')
                ->where('movings.store_id', Auth::user()->store_id)
                ->where('movings.operation', 1)
                ->where(function ($qq) {
                    $qq->whereDate('movings.created_at', '<', request('date'))
                        ->orWhere(function ($q) {
                            $q->whereDate('movings.created_at', request('date'))->whereTime('movings.created_at', '<', request('time'));
                        });
                })
                ->sum('count') ?? 0;

            $item['moving_to'] = (float)Product::query()
                ->where('products.id', $product->id)
                ->join('moving_products', 'moving_products.product_id', 'products.id')
                ->join('movings', 'movings.id', 'moving_products.moving_id')
                ->where('movings.store_id', Auth::user()->store_id)
                ->where('movings.operation', 2)
                ->where(function ($qq) {
                    $qq->whereDate('movings.created_at', '<', request('date'))
                        ->orWhere(function ($q) {
                            $q->whereDate('movings.created_at', request('date'))->whereTime('movings.created_at', '<', request('time'));
                        });
                })
                ->sum('count') ?? 0;
            $item['overage'] = (float)Product::query()
                ->where('products.id', $product->id)
                ->join('inventory_products', 'inventory_products.product_id', 'products.id')
                ->join('inventories', 'inventories.id', 'inventory_products.inventory_id')
                ->where('inventories.store_id', Auth::user()->store_id)
                ->where(function ($qq) {
                    $qq->whereDate('inventories.created_at', '<', request('date'))
                        ->orWhere(function ($q) {
                            $q->whereDate('inventories.created_at', request('date'))->whereTime('inventories.created_at', '<', request('time'));
                        });
                })
                ->latest('inventories.created_at')
                ->first()?->overage ?? 0;

            $item['reject'] = (float)Product::query()
                ->where('products.id', $product->id)
                ->join('reject_products', 'reject_products.product_id', 'products.id')
                ->join('rejects', 'rejects.id', 'reject_products.reject_id')
                ->where('rejects.store_id', Auth::user()->store_id)
                ->where('rejects.source', 1)
                ->where(function ($qq) {
                    $qq->whereDate('rejects.created_at', '<', request('date'))
                        ->orWhere(function ($q) {
                            $q->whereDate('rejects.created_at', request('date'))->whereTime('rejects.created_at', '<', request('time'));
                        });
                })
                ->sum('count') ?? 0;;

            $item['reject_all'] = (float)$item['reject'] + $item['refund_producer'] + $item['order'] + $item['moving_to'];

            $data[] = $item;
        }

        return response()->json($data);
    }

    public function zReport(Request $request)
    {

        $reports = Report::query()
            ->where('store_id', Auth::user()->store_id)
            ->where('name', 'z-report')
            ->latest()
            ->when($request->has('date_from'), function ($q) {
                $q->whereDate('created_at', '>=', \request('date_from'));
            })
            ->when($request->has('date_to'), function ($q) {
                $q->whereDate('created_at', '<=', \request('date_to'));
            })
            ->limit(100)
            ->get();

        return response()->json($reports);
    }

    public function zReportPrint(Report $report)
    {
        return view('pdf.z-report', ['data' => $report->body]);
    }
}
