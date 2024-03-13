<?php

namespace App\Http\Controllers\Admin;

use App\Actions\ReceiptStoreAction;
use App\Actions\RejectStoreAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\OrderManyUpdateRequest;
use App\Http\Requests\Admin\OrderUpdateRequest;
use App\Models\Inventory;
use App\Models\InventoryProduct;
use App\Models\Receipt;
use App\Models\Reject;
use App\Models\User;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;

class InventoryController extends Controller
{
    public function index(Request $request)
    {
        $storeId = $request->get('store_id');
        $counteragentId = $request->get('counteragent_id');
        $userId = $request->get('user_id');

        return view('admin.inventory.index', compact( 'storeId', 'userId','counteragentId'));
    }

    public function edit(Inventory $inventory): View
    {

        return view('admin.inventory.edit', compact('inventory'));

    }

    public function update(OrderUpdateRequest $request, Inventory $inventory)
    {
        DB::beginTransaction();
        try {
            $inventory->receipts()->forceDelete();
            $inventory->rejects()->forceDelete();

            $receiptData = [];
            $rejectData = [];

            foreach ($request->get('products') as $productId => $productData) {
                $inventoryProduct  = InventoryProduct::findOrFail($productId);
                $inventoryProduct->count = $productData['count'];

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
                $inventoryProduct->save();
            }


//        //Излишки
            if (count($receiptData) > 0) {
                $receiptStoreAction = new ReceiptStoreAction();
                $receiptData['storage_id'] = $inventory->user->storage_id;
                $receiptData['store_id'] = $inventory->user->store_id;
                $receiptData['organization_id'] = $inventory->user->organization_id;
                $receiptData['operation'] = 2;
                $receiptData['inventory_id'] = $inventory->id;
                $receiptData['description'] = "На основании инвентаризации №$inventory->id от " . $inventory->created_at;

                $receiptStoreAction->execute($receiptData);

            }
//        //Недостачи
            if (count($rejectData) > 0) {
                $rejectStoreAction = new RejectStoreAction();
                $rejectData['storage_id'] =$inventory->user->storage_id;
                $rejectData['store_id'] = $inventory->user->store_id;
                $rejectData['organization_id'] = $inventory->user->organization_id;
                $rejectData['inventory_id'] = $inventory->id;
                $rejectData['description'] = "На основании инвентаризации №$inventory->id от " . $inventory->created_at;
                $rejectStoreAction->execute($rejectData);
            }


            $inventory->status = 2;
            $inventory->save();


            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage());
        }


        return redirect()->route('admin.inventory.show', $inventory->id);
    }

    public function show($inventoryId)
    {
        $inventory = Inventory::findOrFail($inventoryId);
        $rejects = Reject::where('inventory_id',$inventoryId)->get();
        $receipts = Receipt::where('inventory_id',$inventoryId)->get();

        return view('admin.inventory.show', compact('inventory','receipts','rejects'));
    }

    public function delete(Inventory $inventory)
    {

        foreach ($inventory->receipts as $receipt) {
            $receipt->delete();
        }
        foreach ($inventory->rejects as $reject) {
            $reject->delete();
        }

        $inventory->delete();

        return redirect()->back();
    }
    public function recover($id)
    {

        $inventory = Inventory::where('id', $id)->withTrashed()->first();
        $inventory->removed_at = null;
        $inventory->deleted_at = null;
        $inventory->save();

        return redirect()->back();
    }

    public function history(Inventory $inventory)
    {
        return \view('admin.inventory.history', compact('inventory'));
    }


}
