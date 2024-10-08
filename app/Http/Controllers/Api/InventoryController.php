<?php

namespace App\Http\Controllers\Api;

use App\Actions\ReceiptStoreAction;
use App\Actions\RejectStoreAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\InventoryAddProductRequest;
use App\Http\Requests\Api\InventoryAddReceiptRequest;
use App\Http\Requests\Api\InventoryIndexRequest;
use App\Http\Requests\Api\InventoryStoreRequest;
use App\Http\Requests\Api\InventoryUpdateRequest;
use App\Http\Requests\Api\ProductIndexRequest;
use App\Http\Resources\ProductResource;
use App\Models\Counteragent;
use App\Models\Inventory;
use App\Models\Moving;
use App\Models\Product;
use App\Models\ProductPriceType;
use App\Models\ReceiptProduct;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use DB;
use Dflydev\DotAccessData\Data;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InventoryController extends Controller
{
    public function _index(Request $request)
    {

        $storeId = Auth::user()->store_id;
        //приход - расход
        // поступления +  перемешение с склада + возврат от клиента  - продажа  - возврат поставщику  - перемешеие на склад = остаток

        $result = DB::table('products')
            ->select('products.name', 'products.id AS product_id', 'id_1c', 'measure', 'article',
                DB::raw('COALESCE(moving_from.sum_count, 0) AS moving_from'),
                DB::raw('COALESCE(moving_to.sum_count, 0) AS moving_to'),
                DB::raw('COALESCE(receipt.sum_count, 0) AS receipt'),
                DB::raw('COALESCE(orderProduct.sum_count, 0) AS sale'),
//                DB::raw('COALESCE(refund.sum_count, 0) AS refund'),
                DB::raw('COALESCE(refund_producer.sum_count, 0) AS refund_producer'),
                DB::raw('COALESCE(reject.sum_count, 0) AS reject'),


//                DB::raw('COALESCE(receipt.sum_count, 0) + COALESCE(moving_from.sum_count, 0) +  COALESCE(refund.sum_count, 0) - COALESCE(orderProduct.sum_count, 0) - COALESCE(refund_producer.sum_count, 0) - COALESCE(moving_to.sum_count, 0)  - COALESCE(reject.sum_count, 0) AS remains' )
                DB::raw('COALESCE(receipt.sum_count, 0) + COALESCE(moving_from.sum_count, 0)  - COALESCE(orderProduct.sum_count, 0) - COALESCE(refund_producer.sum_count, 0) - COALESCE(moving_to.sum_count, 0)  - COALESCE(reject.sum_count, 0) AS remains')
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
//            ->leftJoin(
//                DB::raw("(SELECT refund_products.product_id, SUM(refund_products.count) AS sum_count
//                    FROM refund_products
//                    INNER JOIN refunds ON refunds.id = refund_products.refund_id
//                    WHERE refunds.store_id = $storeId
//                    GROUP BY refund_products.product_id) AS refund"),
//                'refund.product_id', '=', 'products.id'
//            )
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
            AND orders.check_number IS NOT NULL
            GROUP BY order_products.product_id) AS orderProduct"),
                'orderProduct.product_id', '=', 'products.id'
            )
            ->groupBy('products.id')
            ->orderBy('products.name')
            ->having('remains', '>', 0)
            ->get();

        return response()->json($result);
    }

    public function index(InventoryIndexRequest $request)
    {
        //приход - расход
        // поступления +  перемешение с склада + возврат от клиента  - продажа  - возврат поставщику  - перемешеие на склад = остаток

//        $movings = Moving::query()
//            ->join('moving_products', 'movings.id', '=', 'moving_products.moving_id')
//            ->join('products', 'products.id', '=', 'moving_products.product_id')
//            ->select('movings.id','movings.created_at','products.name')
//            ->where(function ($qq){
//                $qq->whereDate('movings.created_at', '<', request('date') ?? now())
//                ->orWhere(function ($q) {
//                    $q->whereDate('movings.created_at', request('date') ?? now())->whereTime('movings.created_at','<',request('time') ?? now()->toTimeString());
//                });
//            })
////            ->where('movings.operation', 1)
////            ->where('movings.store_id', Auth::user()->store_id)
//            ->latest()
//            ->groupBy('movings.id','products.id')
//            ->get();
//        return response()->json($movings);


        $storeId = Auth::user()->store_id;

        $result = Product::select(
            'products.name',
            'products.id AS product_id',
            'id_1c',
            'measure',
            'article',
            DB::raw('COALESCE(moving_from.sum_count, 0) AS moving_from'),
            DB::raw('COALESCE(moving_to.sum_count, 0) AS moving_to'),
            DB::raw('COALESCE(receipt.sum_count, 0) AS receipt'),
            DB::raw('COALESCE(orderProduct.sum_count, 0) AS sale'),
            DB::raw('COALESCE(refund_producer.sum_count, 0) AS refund_producer'),
            DB::raw('COALESCE(reject.sum_count, 0) AS reject'),
            DB::raw('COALESCE(receipt.sum_count, 0) + COALESCE(moving_from.sum_count, 0)  - COALESCE(orderProduct.sum_count, 0) - COALESCE(refund_producer.sum_count, 0) - COALESCE(moving_to.sum_count, 0)  - COALESCE(reject.sum_count, 0) AS remains')
        )
            ->leftJoinSub(
                function ($query) use ($storeId) {
                    $query->select(
                        'moving_products.product_id',
                        DB::raw('SUM(moving_products.count) AS sum_count')
                    )
                        ->from('moving_products')
                        ->join('movings', 'movings.id', '=', 'moving_products.moving_id')
                        ->where('movings.operation', 1)
                        ->where('movings.store_id', $storeId)
                        ->where(function ($qq) {
                            $qq->whereDate('movings.created_at', '<', request('date') ?? now())
                                ->orWhere(function ($q) {
                                    $q->whereDate('movings.created_at', request('date') ?? now())->whereTime('movings.created_at', '<', request('time') ?? now()->toTimeString());
                                });
                        })
                        ->groupBy('moving_products.product_id');
                },
                'moving_from',
                'moving_from.product_id',
                '=',
                'products.id'
            )
            ->leftJoinSub(
                function ($query) use ($storeId) {
                    $query->select(
                        'moving_products.product_id',
                        DB::raw('SUM(moving_products.count) AS sum_count')
                    )
                        ->from('moving_products')
                        ->join('movings', 'movings.id', '=', 'moving_products.moving_id')
                        ->where('movings.operation', 2)
                        ->where('movings.store_id', $storeId)
                        ->where(function ($qq) {
                            $qq->whereDate('movings.created_at', '<', request('date') ?? now())
                                ->orWhere(function ($q) {
                                    $q->whereDate('movings.created_at', request('date') ?? now())->whereTime('movings.created_at', '<', request('time') ?? now()->toTimeString());
                                });
                        })
                        ->groupBy('moving_products.product_id');
                },
                'moving_to',
                'moving_to.product_id',
                '=',
                'products.id'
            )
            ->leftJoinSub(
                function ($query) use ($storeId) {
                    $query->select(
                        'reject_products.product_id',
                        DB::raw('SUM(reject_products.count) AS sum_count')
                    )
                        ->from('reject_products')
                        ->join('rejects', 'rejects.id', '=', 'reject_products.reject_id')
                        ->where('rejects.store_id', $storeId)
                        ->where(function ($qq) {
                            $qq->whereDate('rejects.created_at', '<', request('date') ?? now())
                                ->orWhere(function ($q) {
                                    $q->whereDate('rejects.created_at', request('date') ?? now())->whereTime('rejects.created_at', '<', request('time') ?? now()->toTimeString());
                                });
                        })
                        ->groupBy('reject_products.product_id');
                },
                'reject',
                'reject.product_id',
                '=',
                'products.id'
            )
            ->leftJoinSub(
                function ($query) use ($storeId) {
                    $query->select(
                        'refund_producer_products.product_id',
                        DB::raw('SUM(refund_producer_products.count) AS sum_count')
                    )
                        ->from('refund_producer_products')
                        ->join('refund_producers', 'refund_producers.id', '=', 'refund_producer_products.refund_producer_id')
                        ->where('refund_producers.store_id', $storeId)
                        ->where(function ($qq) {
                            $qq->whereDate('refund_producers.created_at', '<', request('date') ?? now())
                                ->orWhere(function ($q) {
                                    $q->whereDate('refund_producers.created_at', request('date') ?? now())->whereTime('refund_producers.created_at', '<', request('time') ?? now()->toTimeString());
                                });
                        })
                        ->groupBy('refund_producer_products.product_id');
                },
                'refund_producer',
                'refund_producer.product_id',
                '=',
                'products.id'
            )
            ->leftJoinSub(
                function ($query) use ($storeId) {
                    $query->select(
                        'receipt_products.product_id',
                        DB::raw('SUM(receipt_products.count) AS sum_count')
                    )
                        ->from('receipt_products')
                        ->join('receipts', 'receipts.id', '=', 'receipt_products.receipt_id')
                        ->where('receipts.store_id', $storeId)
                        ->where(function ($qq) {
                            $qq->whereDate('receipts.created_at', '<', request('date') ?? now())
                                ->orWhere(function ($q) {
                                    $q->whereDate('receipts.created_at', request('date') ?? now())->whereTime('receipts.created_at', '<', request('time') ?? now()->toTimeString());
                                });
                        })
                        ->groupBy('receipt_products.product_id');
                },
                'receipt',
                'receipt.product_id',
                '=',
                'products.id'
            )
            ->leftJoinSub(
                function ($query) use ($storeId) {
                    $query->select(
                        'order_products.product_id',
                        DB::raw('SUM(order_products.count) AS sum_count')
                    )
                        ->from('order_products')
                        ->join('orders', 'orders.id', '=', 'order_products.order_id')
                        ->where('orders.store_id', $storeId)
                        ->whereNotNull('orders.check_number')
                        ->where(function ($qq) {
                            $qq->whereDate('orders.created_at', '<', request('date') ?? now())
                                ->orWhere(function ($q) {
                                    $q->whereDate('orders.created_at', request('date') ?? now())->whereTime('orders.created_at', '<', request('time') ?? now()->toTimeString());
                                });
                        })
                        ->groupBy('order_products.product_id');
                },
                'orderProduct',
                'orderProduct.product_id',
                '=',
                'products.id'
            )
            ->groupBy('products.id')
            ->orderBy('products.name')
            ->having('remains', '>', 0)
            ->get();

        return response()->json($result);
    }

    public function store(InventoryStoreRequest $request)
    {
        $inventory = Inventory::create([
            'user_id' => Auth::id(),
            'store_id' => Auth::user()->store_id,
            'date' => $request->has('date') ? $request->get('date') : now(),
            'time' => $request->has('time') ? $request->get('time') : now()->toTimeString(),
        ]);

        foreach ($request->get('products') as $item) {
            $product = Product::findOrFail($item['product_id']);

            $productPriceType = $product->prices()->where('price_type_id', 3)->first();
            $item['price'] = $productPriceType ? $productPriceType->price : 0;

            $inventory->products()->create($item);
        }

        return response()->json($inventory);
    }

    public function active(Inventory $inventory, ReceiptStoreAction $receiptStoreAction, RejectStoreAction $rejectStoreAction)
    {
        if ($inventory->status == 2) {
            return response()->json(['message' => 'документ уже активен'], 400);
        }
        try {
            DB::beginTransaction();
            $receiptData = [];
            $rejectData = [];

            foreach ($inventory->products as $inventoryProduct) {
                $product = $inventoryProduct->product;

                if ($inventoryProduct->remains < $inventoryProduct->count) {
                    //Излишки
                    $inventoryProduct->overage = $inventoryProduct->count - $inventoryProduct->remains;
                    $receiptData['products'] [] = [
                        'product_id' => $inventoryProduct->product_id,
                        'count' => $inventoryProduct->overage,
                    ];

                }
                if ($inventoryProduct->remains > $inventoryProduct->count) {
                    //Недостачи
                    $inventoryProduct->shortage = $inventoryProduct->remains - $inventoryProduct->count;
                    $rejectData['products'] [] = [
                        'product_id' => $inventoryProduct->product_id,
                        'count' => $inventoryProduct->shortage,
                    ];
                }

                $productPriceType = $product->prices()->where('price_type_id', 3)->first();
                $inventoryProduct->price = $productPriceType ? $productPriceType->price : 0;

                $inventoryProduct->save();

            }


//        //Излишки
            if (count($receiptData) > 0) {
                $receiptData['storage_id'] = Auth::user()->storage_id;
                $receiptData['store_id'] = Auth::user()->store_id;
                $receiptData['organization_id'] = Auth::user()->organization_id;
                $receiptData['operation'] = 2;
                $receiptData['inventory_id'] = $inventory->id;
                $receiptData['description'] = "На основании инвентаризации №$inventory->id от " . $inventory->created_at;

                $receiptStoreAction->execute($receiptData);

            }
//        //Недостачи
            if (count($rejectData) > 0) {
                $rejectData['storage_id'] = Auth::user()->storage_id;
                $rejectData['store_id'] = Auth::user()->store_id;
                $rejectData['organization_id'] = Auth::user()->organization_id;
                $rejectData['inventory_id'] = $inventory->id;
                $rejectData['description'] = "На основании инвентаризации №$inventory->id от " . $inventory->created_at;
                $rejectStoreAction->execute($rejectData);
            }


            $inventory->status = 2;
            $inventory->save();
            DB::commit();
            return response()->json($inventory);
        } catch (\Exception $exception) {
            DB::rollBack();
            return response()->json(['message' => $exception->getMessage()], 400);
        }
    }

    public function update(InventoryStoreRequest $request, Inventory $inventory)
    {

        foreach ($request->get('products') as $item) {

            $inventory->products()->updateOrCreate([
                'product_id' => $item['product_id']
            ], $item);
        }

        return response()->json($inventory);
    }

    public function addReceipt(InventoryAddReceiptRequest $request, ReceiptStoreAction $receiptStoreAction)
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

    public function history(Request $request)
    {
        $inventories = Inventory::query()
            ->where('user_id', Auth::id())
            ->with(['products', 'products.product', 'store'])
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

    public function html(Inventory $inventory)
    {


        return view('pdf.inventory', compact('inventory'));

//        $pdf = Pdf::loadView('pdf.inventory',compact('inventory'));
//
//        return $pdf->download('inventory.pdf');
    }

    public function addProduct(InventoryAddProductRequest $request)
    {
        DB::beginTransaction();
        $inventory = Inventory::findOrFail($request->get('inventory_id'));
        try {
            if ($inventory->status == 2) {
                throw new Exception('документ уже активен');
            }
            $exists = $inventory->products()->where('product_id', $request->get('product_id'))->exists();
            if ($exists) {
                throw new Exception('товар уже добавлен');
            }
//            $data['operation'] = 2;
//            $data['store_id'] = Auth::user()->store_id;
//            $data['storage_id'] = Auth::user()->storage_id;
//            $data['organization_id'] = Auth::user()->organization_id;
//            $data['description'] = 'добавлен через инвентор: ' . $inventory->id . ' от ' . $inventory->created_at;
//            $data['products'][] = [
//                'product_id' => $request->get('product_id'),
//                'count' => $request->get('count')
//            ];
//
//            $receiptStoreAction = new ReceiptStoreAction();
//            $receiptStoreAction->execute($data);

            $product = Product::findOrFail($request->get('product_id'));
            $priceType = $product->prices()->where('price_type_id', 3)->first();
            if (!$priceType) throw new Exception("price not found: $product->id");

            $inventory->products()->create([
                'product_id' => $request->get('product_id'),
                'count' => 0,
                'receipt' => 0,
                'remains' => 0,
                'price' => $priceType->price,
            ]);

            DB::commit();

            return response()->json($inventory);

        } catch (Exception $exception) {
            DB::rollBack();
            return response()->json(['message' => $exception->getMessage()], 400);
        }
    }
}
