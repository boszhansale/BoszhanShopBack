<?php

namespace App\Http\Controllers\Admin;

use App\Actions\OrderPriceAction;
use App\Exports\Admin\OrderProductExcelExport;
use App\Exports\Excel\OrderExcelExport;
use App\Http\Controllers\Controller;
use App\Http\Livewire\Admin\OrderProductIndex;
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
        $discountPhone = $request->get('discount_phone');
        $startCreatedAt = $request->get('start_created_at');
        $endCreatedAt = $request->get('end_created_at');

        return view('admin.order.index', compact( 'storeId', 'userId','counteragentId','discountPhone','startCreatedAt','endCreatedAt'));
    }

    public function productIndex(Request $request)
    {
        $storeId = $request->get('store_id');
        $userId = $request->get('user_id');

        return view('admin.order.product_index', compact( 'storeId', 'userId'));
    }

    public function productExcel(Request $request)
    {
        $orders = Order::query()
//            ->join('stores', 'stores.id', 'orders.store_id')
            ->join('order_products','order_products.order_id','orders.id')
            ->join('products','products.id','order_products.product_id')
            ->whereNotNull('check_number')
            ->when($request->get('search'), function ($q) {
                return $q->where('orders.id', 'LIKE', \request('search') . '%');
            })
            ->when($request->get('userId'), function ($q) {
                return $q->where('orders.user_id', \request('userId'));
            })
            ->when($request->get('store_id'), function ($q) {
                return $q->where('orders.store_id', \request('store_id'));
            })
            ->when($request->get('start_created_at'), function ($q) {
                return $q->whereDate('orders.created_at', '>=', \request('start_created_at'));
            })
            ->when($request->get('end_created_at'), function ($q) {
                return $q->whereDate('orders.created_at', '<=', \request('end_created_at'));
            })
            ->selectRaw('store_id,product_id,products.name,price,SUM(count) as count,SUM(all_price) as all_price,orders.user_id')
            ->groupBy('store_id','product_id','price','orders.user_id')
            ->orderBy('products.name')
            ->orderBy('store_id')
            ->get();

        return Excel::download(new OrderProductExcelExport($orders), 'order_products.xlsx');


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
