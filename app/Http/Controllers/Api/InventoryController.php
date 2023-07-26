<?php

namespace App\Http\Controllers\Api;

use App\Actions\ReceiptStoreAction;
use App\Actions\RejectStoreAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\InventoryAddReceiptRequest;
use App\Http\Requests\Api\InventoryStoreRequest;
use App\Http\Requests\Api\ProductIndexRequest;
use App\Http\Resources\ProductResource;
use App\Models\Counteragent;
use App\Models\Inventory;
use App\Models\Product;
use App\Models\ReceiptProduct;
use Carbon\Carbon;
use DB;
use Dflydev\DotAccessData\Data;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InventoryController extends Controller
{
    public function index()
    {
        $storeId = Auth::user()->store_id;
        //приход - расход
        // поступления +  перемешение с склада + возврат от клиента  - продажа  - возврат поставщику  - перемешеие на склад = остаток

        $result = DB::table('products')
            ->select('products.name', 'products.id AS product_id','id_1c','measure','article',
                DB::raw('COALESCE(moving_from.sum_count, 0) AS moving_from'),
                DB::raw('COALESCE(moving_to.sum_count, 0) AS moving_to'),
                DB::raw('COALESCE(receipt.sum_count, 0) AS receipt'),
                DB::raw('COALESCE(orderProduct.sum_count, 0) AS sale'),
                DB::raw('COALESCE(refund.sum_count, 0) AS refund'),
                DB::raw('COALESCE(refund_producer.sum_count, 0) AS refund_producer'),
                DB::raw('COALESCE(reject.sum_count, 0) AS reject'),


                DB::raw('COALESCE(receipt.sum_count, 0) + COALESCE(moving_from.sum_count, 0) +  COALESCE(refund.sum_count, 0) - COALESCE(orderProduct.sum_count, 0) - COALESCE(refund_producer.sum_count, 0) - COALESCE(moving_to.sum_count, 0)  - COALESCE(reject.sum_count, 0) AS remains' )
            )
            ->leftJoin(
                DB::raw("(SELECT moving_products.product_id, SUM(moving_products.count) AS sum_count
                    FROM moving_products
                    INNER JOIN movings ON movings.id = moving_products.moving_id
                    WHERE movings.operation = 1
                    AND movings.store_id = $storeId
                    GROUP BY moving_products.product_id) AS moving_from"),
                'moving_from.product_id', '=', 'products.id'
            )
            ->leftJoin(
                DB::raw("(SELECT moving_products.product_id, SUM(moving_products.count) AS sum_count
                    FROM moving_products
                    INNER JOIN movings ON movings.id = moving_products.moving_id
                    WHERE movings.operation = 2
                    AND movings.store_id = $storeId
                    GROUP BY moving_products.product_id) AS moving_to"),
                'moving_to.product_id', '=', 'products.id'
            )
            ->leftJoin(
                DB::raw("(SELECT refund_products.product_id, SUM(refund_products.count) AS sum_count
                    FROM refund_products
                    INNER JOIN refunds ON refunds.id = refund_products.refund_id
                    WHERE refunds.store_id = $storeId
                    GROUP BY refund_products.product_id) AS refund"),
                'refund.product_id', '=', 'products.id'
            )
            ->leftJoin(
                DB::raw("(SELECT reject_products.product_id, SUM(reject_products.count) AS sum_count
                    FROM reject_products
                    INNER JOIN rejects ON rejects.id = reject_products.reject_id
                    WHERE rejects.store_id = $storeId
                    GROUP BY reject_products.product_id) AS reject"),
                'reject.product_id', '=', 'products.id'
            )
            ->leftJoin(
                DB::raw("(SELECT refund_producer_products.product_id, SUM(refund_producer_products.count) AS sum_count
                    FROM refund_producer_products
                    INNER JOIN refund_producers ON refund_producers.id = refund_producer_products.refund_producer_id
                    WHERE refund_producers.store_id = $storeId
                    GROUP BY refund_producer_products.product_id) AS refund_producer"),
                'refund_producer.product_id', '=', 'products.id'
            )
            ->leftJoin(
                DB::raw("(SELECT receipt_products.product_id, SUM(receipt_products.count) AS sum_count
            FROM receipt_products
            JOIN receipts ON receipts.id = receipt_products.receipt_id
            WHERE receipts.store_id = $storeId
            GROUP BY receipt_products.product_id) AS receipt"),
                'receipt.product_id', '=', 'products.id'
            )
            ->leftJoin(
                DB::raw("(SELECT order_products.product_id, SUM(order_products.count) AS sum_count
            FROM order_products
            JOIN orders ON orders.id  = order_products.order_id
            WHERE orders.store_id = $storeId
            GROUP BY order_products.product_id) AS orderProduct"),
                'orderProduct.product_id', '=', 'products.id'
            )
            ->groupBy('products.id')
            ->having('remains', '>', 0)
            ->get();

        return response()->json($result);
    }

    public function store(InventoryStoreRequest $request, ReceiptStoreAction $receiptStoreAction, RejectStoreAction $rejectStoreAction)
    {
        $inventory =  Inventory::create(['user_id' => Auth::id(),'store_id' => Auth::user()->store_id]);
        $receiptData = [];
        $rejectData = [];

        foreach ($request->get('products') as $item) {
            if ($item['remains'] < $item['count']){
                //Излишки
                $item['overage'] = $item['count'] - $item['remains'];
                $receiptData['products'] [] = [
                    'product_id' => $item['product_id'],
                    'count' => $item['overage'],
                ];

            }
            if ($item['remains'] > $item['count']){
                //Недостачи
                $item['shortage'] = $item['remains'] - $item['count'];
                $rejectData['products'] [] = [
                    'product_id' => $item['product_id'],
                    'count' => $item['shortage'],
                ];
            }
            $inventory->products()->create($item);
        }
        if (count($receiptData) > 0){
            $receiptData['storage_id'] = Auth::user()->storage_id;
            $receiptData['store_id'] = Auth::user()->store_id;
            $receiptData['organization_id'] = Auth::user()->organization_id;
            $receiptData['operation'] = 2;
            $receiptData['inventory_id'] = $inventory->id;
            $receiptData['description'] = "На основании инвентаризации №$inventory->id от ".$inventory->created_at->format('d.m.Y');

            $receiptStoreAction->execute($receiptData);

        }
        if (count($rejectData) > 0)
        {
            $receiptData['storage_id'] = Auth::user()->storage_id;
            $receiptData['store_id'] = Auth::user()->store_id;
            $receiptData['organization_id'] = Auth::user()->organization_id;
            $receiptData['inventory_id'] = $inventory->id;
            $receiptData['description'] = "На основании инвентаризации №$inventory->id от ".$inventory->created_at->format('d.m.Y');
            $rejectStoreAction->execute($receiptData);
        }

        return response()->json($inventory);
    }

    public function addReceipt(InventoryAddReceiptRequest $request,ReceiptStoreAction $receiptStoreAction )
    {
        $data['operation'] = 2;
        $data['store_id'] = Auth::user()->store_id;
        $data['storage_id'] = Auth::user()->storage_id;
        $data['organization_id'] = Auth::user()->organization_id;
        $data['description'] = 'добавлен через инвентор';
        $data['products'][] = [
            'product_id' => $request->get('product_id'),
            'count' => $request->get('count')
        ];


        $receipt = $receiptStoreAction->execute($data);

        return response()->json($receipt);
    }

    public function history(Request $request){
        $inventories = Inventory::query()
            ->where('user_id',Auth::id())
            ->with(['products','products.product','store'])
            ->when($request->has('date_from'),function ($q){
                $q->whereDate('created_at','>=',request('date_from'));
            })
            ->when($request->has('date_to'),function ($q){
                $q->whereDate('created_at','<=',request('date_to'));
            })
            ->get();
        return response()->json($inventories);
    }
}
