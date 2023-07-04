<?php

namespace App\Http\Controllers\Admin;

use App\Actions\OrderPriceAction;
use App\Exports\Excel\OrderExcelExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\OrderManyUpdateRequest;
use App\Http\Requests\Admin\OrderUpdateRequest;
use App\Models\Refund;
use App\Models\PaymentStatus;
use App\Models\PaymentType;
use App\Models\Status;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;

class RefundController extends Controller
{
    public function index(Request $request)
    {
        $storeId = $request->get('store_id');
        $counteragentId = $request->get('counteragent_id');
        $userId = $request->get('user_id');

        return view('admin.refund.index', compact( 'storeId', 'userId','counteragentId'));
    }

    public function edit(Refund $refund): View
    {
        return view('admin.refund.edit', compact('refund'));
    }

    public function update(OrderUpdateRequest $request, Refund $refund)
    {
        $refund->update($request->validated());

        return redirect()->back();
    }

    public function show($orderId)
    {
        $refund = Refund::withTrashed()->find($orderId);

        return view('admin.refund.show', compact('refund'));
    }

    public function delete(Refund $refund)
    {
        $refund->delete();

        return redirect()->back();
    }

    public function remove(Refund $store)
    {
        $store->removed_at = now();
        $store->save();
        return redirect()->back();
    }

    public function recover($id)
    {

        $refund = Refund::where('id', $id)->withTrashed()->first();
        $refund->removed_at = null;
        $refund->deleted_at = null;
        $refund->save();

        return redirect()->back();
    }

    public function history(Refund $refund)
    {
        return view('admin.refund.history', compact('refund'));
    }

}
