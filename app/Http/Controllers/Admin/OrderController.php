<?php

namespace App\Http\Controllers\Admin;

use App\Actions\OrderPriceAction;
use App\Exports\Excel\OrderExcelExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\OrderManyUpdateRequest;
use App\Http\Requests\Admin\OrderUpdateRequest;
use App\Models\Order;
use App\Models\PaymentStatus;
use App\Models\PaymentType;
use App\Models\Status;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $storeId = $request->get('store_id');
        $counteragentId = $request->get('counteragent_id');
        $userId = $request->get('user_id');

        return view('admin.order.index', compact( 'storeId', 'userId','counteragentId'));
    }

    public function edit(Order $order): View
    {
        $salesreps = User::query()
            ->where('users.role_id', 1)
            ->where('users.status', 1)
            ->select('users.*')
            ->orderBy('users.name')
            ->get();
        $drivers = User::query()
            ->where('users.role_id', 2)
            ->where('users.status', 1)
            ->select('users.*')
            ->orderBy('users.name')
            ->get();

        $statuses = Status::all();
        $paymentTypes = PaymentType::all();
        $paymentStatuses = PaymentStatus::all();

        return view('admin.order.edit', compact('order', 'salesreps', 'drivers', 'statuses', 'paymentTypes', 'paymentStatuses'));
    }

    public function update(OrderUpdateRequest $request, Order $order)
    {
        $order->update($request->validated());

        return redirect()->back();
    }

    public function show($orderId)
    {
        $order = Order::withTrashed()->find($orderId);

        return view('admin.order.show', compact('order'));
    }

    public function delete(Order $order)
    {
        $order->delete();

        return redirect()->back();
    }

    public function remove(Order $store)
    {
        $store->removed_at = now();
        $store->save();
        return redirect()->back();
    }

    public function recover($id)
    {

        $order = Order::where('id', $id)->withTrashed()->first();
        $order->removed_at = null;
        $order->deleted_at = null;
        $order->save();

        return redirect()->back();
    }

    public function history(Order $order)
    {
        return \view('admin.order.history', compact('order'));
    }

}
