<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Receipt;
use App\Models\ReceiptProduct;
use DB;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReceiptController extends Controller
{
    public function index(Request $request)
    {
        $storeId = $request->get('store_id');
        $counteragentId = $request->get('counteragent_id');
        $userId = $request->get('user_id');

        return view('admin.receipt.index', compact( 'storeId', 'userId','counteragentId'));
    }

    public function edit(Receipt $receipt): View
    {
        return view('admin.receipt.edit', compact('receipt'));
    }

    public function update(Request $request, Receipt $receipt)
    {
        DB::beginTransaction();
        try {
            foreach ($request->get('products') as $productId => $item) {
                $receiptProduct = ReceiptProduct::findOrFail($productId);
                $receiptProduct->count = $item['count'];
                $receiptProduct->all_price = $receiptProduct->price * $item['count'];
                $receiptProduct->save();
            }
            $receipt->total_price = $receipt->products()->sum('all_price');
            $receipt->save();
            DB::commit();

            return redirect()->route('admin.receipt.show', $receipt->id)->with('success', 'Отгрузка успешно обновлена');

        } catch (\Exception $e) {
            dd($e->getMessage());
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function show($orderId)
    {
        $receipt = Receipt::withTrashed()->find($orderId);

        return view('admin.receipt.show', compact('receipt'));
    }

    public function delete(Receipt $receipt)
    {
        $receipt->delete();

        return redirect()->back();
    }

    public function remove(Receipt $store)
    {
        $store->removed_at = now();
        $store->save();
        return redirect()->back();
    }

    public function recover($id)
    {

        $receipt = Receipt::where('id', $id)->withTrashed()->first();
        $receipt->removed_at = null;
        $receipt->deleted_at = null;
        $receipt->save();

        return redirect()->back();
    }

    public function history(Receipt $receipt)
    {
        return \view('admin.receipt.history', compact('receipt'));
    }

}
