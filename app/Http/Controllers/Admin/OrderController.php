<?php

namespace App\Http\Controllers\Admin;

use App\Actions\OrderPriceAction;
use App\Exports\Admin\OrderProductExcelExport;
use App\Exports\Admin\OrdersExport;
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
        $query = Order::query();

        $storeId = $request->get('store_id');
        $counteragentId = $request->get('counteragent_id');
        $userId = $request->get('user_id');
        $discountPhone = $request->get('discount_phone');
        $startCreatedAt = $request->get('start_created_at');
        $endCreatedAt = $request->get('end_created_at');

        if ($storeId) {
            $query->where('store_id', $storeId);
        }

        if ($counteragentId) {
            $query->where('counteragent_id', $counteragentId);
        }

        if ($userId) {
            $query->where('user_id', $userId);
        }

        if ($discountPhone) {
            $query->where('discount_phone', $discountPhone);
        }

        if ($startCreatedAt && $endCreatedAt) {
            $query->whereBetween('created_at', [$startCreatedAt, $endCreatedAt]);
        }

        // Сохранение фильтров в сессии
        session(['order_filters' => $request->all()]);

        $orders = $query->paginate(10);

        return view('admin.order.index', compact(
            'orders',
            'storeId',
            'userId',
            'counteragentId',
            'discountPhone',
            'startCreatedAt',
            'endCreatedAt'
        ));
    }



    public function productIndex(Request $request)
    {
        $storeId = $request->get('store_id');
        $userId = $request->get('user_id');

        return view('admin.order.product_index', compact('storeId', 'userId'));
    }

    public function productExcel(Request $request)
    {
        $startCreatedAt = $request->get('start_created_at');
        $endCreatedAt = $request->get('end_created_at');
        $query = Order::query()
            //            ->join('stores', 'stores.id', 'orders.store_id')
            ->join('order_products', 'order_products.order_id', 'orders.id')
            ->join('products', 'products.id', 'order_products.product_id')
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
            ->when($startCreatedAt, function ($q) {
                return $q->whereDate('orders.created_at', '>=', \request('start_created_at'));
            })
            ->when($endCreatedAt, function ($q) {
                return $q->whereDate('orders.created_at', '<=', \request('end_created_at'));
            });


        $totalPrice = $query->sum('all_price');
        $count = $query->sum('order_products.count');
        $orders = $query->selectRaw('store_id,product_id,products.name,products.article,products.measure,price,SUM(count) as count,SUM(all_price) as all_price,orders.user_id')
            ->groupBy('store_id', 'product_id', 'price', 'orders.user_id')
            ->orderBy('products.name')
            ->orderBy('store_id')->get();

        $fileName = 'order_products_' . $startCreatedAt . '_' . $startCreatedAt . '.xlsx';
        return Excel::download(new OrderProductExcelExport($orders, $count, $totalPrice, $startCreatedAt, $endCreatedAt), $fileName);


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

    public function generateReport(Request $request)
    {

        dd($request->query());
        dd(session('order_filters'));

        $filters = $request->all();

        $query = Order::query();

        if (!empty($filters['store_id'])) {
            $query->where('store_id', $filters['store_id']);
        }

        if (!empty($filters['counteragent_id'])) {
            $query->where('counteragent_id', $filters['counteragent_id']);
        }

        if (!empty($filters['user_id'])) {
            $query->where('user_id', $filters['user_id']);
        }

        if (!empty($filters['discount_phone'])) {
            $query->where('discount_phone', $filters['discount_phone']);
        }

        if (!empty($filters['start_created_at']) && !empty($filters['end_created_at'])) {
            $query->whereBetween('created_at', [$filters['start_created_at'], $filters['end_created_at']]);
        }

        $orders = $query->get();

        return Excel::download(new OrdersExport($orders), 'orders_report.xlsx');
    }

}
