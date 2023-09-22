<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\OrderManyUpdateRequest;
use App\Http\Requests\Admin\OrderUpdateRequest;
use App\Models\Reject;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;

class RejectController extends Controller
{
    public function index(Request $request)
    {
        $storeId = $request->get('store_id');
        $counteragentId = $request->get('counteragent_id');
        $userId = $request->get('user_id');

        return view('admin.reject.index', compact( 'storeId', 'userId','counteragentId'));
    }

    public function edit(Reject $order): View
    {
        return view('admin.reject.edit', compact('order'));
    }

    public function update(OrderUpdateRequest $request, Reject $order)
    {
        $order->update($request->validated());

        return redirect()->back();
    }

    public function show(Reject $reject)
    {
        return view('admin.reject.show', compact('reject'));
    }

    public function delete(Reject $order)
    {
        $order->delete();

        return redirect()->back();
    }

    public function remove(Reject $store)
    {
        $store->removed_at = now();
        $store->save();
        return redirect()->back();
    }

    public function recover($id)
    {

        $order = Reject::where('id', $id)->withTrashed()->first();
        $order->removed_at = null;
        $order->deleted_at = null;
        $order->save();

        return redirect()->back();
    }

    public function history(Reject $order)
    {
        return \view('admin.reject.history', compact('order'));
    }

}
