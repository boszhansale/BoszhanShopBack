<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreStoreRequest;
use App\Models\Counteragent;
use App\Models\Report;
use App\Models\Store;
use App\Models\StoreProductDiscount;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StoreController extends Controller
{
    public function index(Request $request)
    {
        $counteragentId = $request->get('counteragent_id');
        $userId = $request->get('user_id');
        return view('admin.store.index', compact('counteragentId','userId'));
    }

    public function create()
    {

        $counteragents = Counteragent::orderBy('name')->get();

        return view('admin.store.create', compact('counteragents'));
    }

    public function store(StoreStoreRequest $request): RedirectResponse
    {
        $store = Store::create($request->validated());
        return redirect()->route('admin.store.index');
    }

    public function edit(Store $store)
    {
        $users = User::query()
            ->where('users.status', 1)
            ->select('users.*')
            ->orderBy('users.name')
            ->get();


        $counteragents = Counteragent::all();
        return view('admin.store.edit', compact('users','store', 'counteragents'));
    }

    public function update(Request $request, Store $store)
    {


        return redirect()->back();
    }

    public function show(Request $request, Store $store)
    {
        $orders = $store->orders()
            ->when($request->has('start_date'), function ($query) {
                return $query->whereDate('orders.delivery_date', '>=', $this->start_date);
            })
            ->when($request->has('end_date'), function ($query) {
                return $query->whereDate('orders.delivery_date', '<=', $this->end_date);
            })
            ->latest()
            ->paginate(50);


        return view('admin.store.show', compact('store', 'orders'));
    }

    public function delete(Store $store)
    {
        $store->delete();
        return redirect()->back();
    }

    public function remove(Store $store)
    {
        $store->removed_at = now();
        $store->save();
        return redirect()->back();
    }

    public function recover($id)
    {
        $store = Store::findOrFail($id);
        $store->removed_at = null;
        $store->save();

        return redirect()->back();
    }

    public function zReport(Store $store)
    {

        return view('admin.store.z-report', ['store' => $store]);
    }
    public function zReportShow(Report $report)
    {
        return view('admin.store.z-report-show', ['data' => $report->body]);
    }


}
