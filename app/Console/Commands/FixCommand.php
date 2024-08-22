<?php

namespace App\Console\Commands;

use App\Imports\MovingImport;
use App\Models\Inventory;
use App\Models\MovingProduct;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Receipt;
use App\Models\ReceiptProduct;
use App\Models\User;
use App\Services\WebKassa\WebKassaService;
use DB;
use Illuminate\Console\Command;
use Maatwebsite\Excel\Facades\Excel;

class FixCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fix';

    protected $storeId = 4;
    protected $end_date;
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    function handle()
    {
        $this->storeId = 6;
//        $this->end_date = '2023-05-31';
        $remains = DB::table('products')
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
            AND movings.store_id = $this->storeId
            " . ($this->end_date ? "AND DATE(movings.created_at) <= '$this->end_date' " : "") .
                    "GROUP BY moving_products.product_id) AS moving_from"),
                'moving_from.product_id', '=', 'products.id'
            )
            ->leftJoin(
                DB::raw("(SELECT moving_products.product_id, SUM(moving_products.count) AS sum_count
            FROM moving_products
            INNER JOIN movings ON movings.id = moving_products.moving_id
            WHERE movings.operation = 2
            AND movings.store_id = $this->storeId
            " .  ($this->end_date ? "AND DATE(movings.created_at) <= '$this->end_date' " : "") .
                    "GROUP BY moving_products.product_id) AS moving_to"),
                'moving_to.product_id', '=', 'products.id'
            )
            ->leftJoin(
                DB::raw("(SELECT reject_products.product_id, SUM(reject_products.count) AS sum_count
            FROM reject_products
            INNER JOIN rejects ON rejects.id = reject_products.reject_id
            WHERE rejects.store_id = $this->storeId
            " .  ($this->end_date ? "AND DATE(rejects.created_at) <= '$this->end_date' " : "") .
                    "GROUP BY reject_products.product_id) AS reject"),
                'reject.product_id', '=', 'products.id'
            )
            ->leftJoin(
                DB::raw("(SELECT refund_producer_products.product_id, SUM(refund_producer_products.count) AS sum_count
            FROM refund_producer_products
            INNER JOIN refund_producers ON refund_producers.id = refund_producer_products.refund_producer_id
            WHERE refund_producers.store_id = $this->storeId
            " . ($this->end_date ? "AND DATE(refund_producers.created_at) <= '$this->end_date' " : "") .
                    "GROUP BY refund_producer_products.product_id) AS refund_producer"),
                'refund_producer.product_id', '=', 'products.id'
            )
            ->leftJoin(
                DB::raw("(SELECT receipt_products.product_id, SUM(receipt_products.count) AS sum_count
        FROM receipt_products
        JOIN receipts ON receipts.id = receipt_products.receipt_id
        WHERE receipts.store_id = $this->storeId
        " . ($this->end_date ? "AND DATE(receipts.created_at) <= '$this->end_date' " : "") .
                    "GROUP BY receipt_products.product_id) AS receipt"),
                'receipt.product_id', '=', 'products.id'
            )
            ->leftJoin(
                DB::raw("(SELECT order_products.product_id, SUM(order_products.count) AS sum_count
        FROM order_products
        JOIN orders ON orders.id  = order_products.order_id
        WHERE orders.store_id = $this->storeId
        AND orders.check_number IS NOT NULL

        " . ($this->end_date ? "AND DATE(orders.created_at) <= '$this->end_date' " : "") .
                    "GROUP BY order_products.product_id) AS orderProduct"),
                'orderProduct.product_id', '=', 'products.id'
            )
            ->orderBy('remains', 'desc')
            ->groupBy('products.id')
            ->having("remains","<", 0)
            ->get();

        $receipt = New Receipt();
        $receipt->store_id = $this->storeId;
        $receipt->user_id = 1;
        $receipt->created_at = now()->subYears(3);
        $receipt->description = 'Превышение остатков';
        $receipt->save();

        foreach ($remains as $r) {
            $count = abs($r->remains);
            $receipt->products()->create([
                'product_id' => $r->product_id,
                'count' => $count,
                'price' => 0,
                'all_price' => 0,
                'comment' => 'Превышение остатков на '. $count.'ш'
            ]);
        }
    }
}
