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
        $result = DB::table('products')
            ->select('products.name', 'products.id AS product_id','id_1c','measure','article',
                DB::raw('COALESCE(moving.sum_count, 0) AS moving'),
                DB::raw('COALESCE(receipt.sum_count, 0) AS receipt'),
                DB::raw('COALESCE(orderProduct.sum_count, 0) AS sale'),
                DB::raw('COALESCE(receipt.sum_count, 0) - COALESCE(orderProduct.sum_count, 0) - COALESCE(moving.sum_count, 0) AS remains')
            )
            ->leftJoin(
                DB::raw('(SELECT moving_products.product_id, SUM(moving_products.count) AS sum_count
            FROM moving_products
            INNER JOIN movings ON movings.id = moving_products.moving_id
            WHERE movings.operation = 1
            GROUP BY moving_products.product_id) AS moving'),
                'moving.product_id', '=', 'products.id'
            )
            ->leftJoin(
                DB::raw('(SELECT receipt_products.product_id, SUM(receipt_products.count) AS sum_count
            FROM receipt_products
            GROUP BY receipt_products.product_id) AS receipt'),
                'receipt.product_id', '=', 'products.id'
            )
            ->leftJoin(
                DB::raw('(SELECT order_products.product_id, SUM(order_products.count) AS sum_count
            FROM order_products
            GROUP BY order_products.product_id) AS orderProduct'),
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

    public function history(){
        $inventories = Inventory::query()
            ->where('user_id',Auth::id())
            ->with(['products','products.product'])
            ->get();
        return response()->json($inventories);
    }
}
