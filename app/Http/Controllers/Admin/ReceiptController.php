<?php

namespace App\Http\Controllers\Admin;

use App\Actions\OrderPriceAction;
use App\Exports\Excel\OrderExcelExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\OrderManyUpdateRequest;
use App\Http\Requests\Admin\OrderUpdateRequest;
use App\Models\Receipt;
use App\Models\PaymentStatus;
use App\Models\PaymentType;
use App\Models\Status;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;

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

    public function update(OrderUpdateRequest $request, Receipt $receipt)
    {
        $receipt->update($request->validated());

        return redirect()->back();
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
