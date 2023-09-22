<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\OrderManyUpdateRequest;
use App\Http\Requests\Admin\OrderUpdateRequest;
use App\Models\Inventory;
use App\Models\Receipt;
use App\Models\Reject;
use App\Models\User;
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
        $inventory->update($request->validated());

        return redirect()->back();
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
        $inventory->delete();

        return redirect()->back();
    }

    public function remove(Inventory $inventory)
    {
        $inventory->removed_at = now();
        $inventory->save();
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
