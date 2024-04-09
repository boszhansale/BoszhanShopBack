<?php

namespace App\Http\Controllers\Admin;

use App\Exports\Admin\RemainExcelExport;
use App\Http\Controllers\Controller;
use App\Models\Store;
use DB;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    //остатки
    public function remain(Store $store)
    {
        return view('admin.report.remain',compact('store'));
    }

    public function remainExcel(Request $request)
    {
        $storeId = $request->get('store_id');
        $endDate = $request->get('end_date');

        $remains =  DB::table('products')
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
            " . ($endDate ? "AND DATE(movings.created_at) <= '$endDate' " : "") .
                    "GROUP BY moving_products.product_id) AS moving_from"),
                'moving_from.product_id', '=', 'products.id'
            )
            ->leftJoin(
                DB::raw("(SELECT moving_products.product_id, SUM(moving_products.count) AS sum_count
            FROM moving_products
            INNER JOIN movings ON movings.id = moving_products.moving_id
            WHERE movings.operation = 2
            AND movings.store_id = $storeId
            " .  ($endDate ? "AND DATE(movings.created_at) <= '$endDate' " : "") .
                    "GROUP BY moving_products.product_id) AS moving_to"),
                'moving_to.product_id', '=', 'products.id'
            )
//            ->leftJoin(
//                DB::raw("(SELECT refund_products.product_id, SUM(refund_products.count) AS sum_count
//                FROM refund_products
//                INNER JOIN refunds ON refunds.id = refund_products.refund_id
//                WHERE refunds.store_id = $storeId
//            " . ($endDate ? "AND refunds.created_at <= '$endDate' " : "") .
//                    "GROUP BY refund_products.product_id) AS refund"),
//                'refund.product_id', '=', 'products.id'
//            )
            ->leftJoin(
                DB::raw("(SELECT reject_products.product_id, SUM(reject_products.count) AS sum_count
            FROM reject_products
            INNER JOIN rejects ON rejects.id = reject_products.reject_id
            WHERE rejects.store_id = $storeId
            " .  ($endDate ? "AND DATE(rejects.created_at) <= '$endDate' " : "") .
                    "GROUP BY reject_products.product_id) AS reject"),
                'reject.product_id', '=', 'products.id'
            )
            ->leftJoin(
                DB::raw("(SELECT refund_producer_products.product_id, SUM(refund_producer_products.count) AS sum_count
            FROM refund_producer_products
            INNER JOIN refund_producers ON refund_producers.id = refund_producer_products.refund_producer_id
            WHERE refund_producers.store_id = $storeId
            " . ($endDate ? "AND DATE(refund_producers.created_at) <= '$endDate' " : "") .
                    "GROUP BY refund_producer_products.product_id) AS refund_producer"),
                'refund_producer.product_id', '=', 'products.id'
            )
            ->leftJoin(
                DB::raw("(SELECT receipt_products.product_id, SUM(receipt_products.count) AS sum_count
        FROM receipt_products
        JOIN receipts ON receipts.id = receipt_products.receipt_id
        WHERE receipts.store_id = $storeId
        " . ($endDate ? "AND DATE(receipts.created_at) <= '$endDate' " : "") .
                    "GROUP BY receipt_products.product_id) AS receipt"),
                'receipt.product_id', '=', 'products.id'
            )
            ->leftJoin(
                DB::raw("(SELECT order_products.product_id, SUM(order_products.count) AS sum_count
        FROM order_products
        JOIN orders ON orders.id  = order_products.order_id
        WHERE orders.store_id = $storeId
        AND orders.check_number IS NOT NULL

        " . ($endDate ? "AND DATE(orders.created_at) <= '$endDate' " : "") .
                    "GROUP BY order_products.product_id) AS orderProduct"),
                'orderProduct.product_id', '=', 'products.id'
            )
            ->orderBy('remains', 'desc')
            ->groupBy('products.id')
            ->having('remains', '<>', 0)
            ->get();

        return Excel::download(new RemainExcelExport($remains), 'remains.xlsx');
    }

    public function discountCard(Store $store)
    {

        return view('admin.report.discount_card',compact('store'));
    }

    public function order(Store $store)
    {

        return view('admin.report.order',compact('store'));
    }

    public function inventor(Store $store)
    {

        return view('admin.report.inventory',compact('store'));
    }

    public function product(Store $store)
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


        return view('admin.report.product',compact('store'));
    }
}
