<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\OrderUpdateRequest;
use App\Models\RefundProducer;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RefundProducerController extends Controller
{
    public function index(Request $request)
    {
        $storeId = $request->get('store_id');
        $counteragentId = $request->get('counteragent_id');
        $userId = $request->get('user_id');

        return view('admin.refundProducer.index', compact( 'storeId', 'userId','counteragentId'));
    }

    public function edit(RefundProducer $refundProducer): View
    {

        return view('admin.refundProducer.edit', compact('refundProducer'));
    }

    public function update(OrderUpdateRequest $request, RefundProducer $refundProducer)
    {
        $refundProducer->update($request->validated());

        return redirect()->back();
    }

    public function show($orderId)
    {
        $refundProducer = RefundProducer::withTrashed()->find($orderId);

        return view('admin.refundProducer.show', compact('refundProducer'));
    }

    public function delete(RefundProducer $refundProducer)
    {
        $refundProducer->delete();

        return redirect()->back();
    }

    public function remove(RefundProducer $store)
    {
        $store->removed_at = now();
        $store->save();
        return redirect()->back();
    }

    public function recover($id)
    {

        $refundProducer = RefundProducer::where('id', $id)->withTrashed()->first();
        $refundProducer->removed_at = null;
        $refundProducer->deleted_at = null;
        $refundProducer->save();

        return redirect()->back();
    }

    public function history(RefundProducer $refundProducer)
    {
        return \view('admin.refundProducer.history', compact('refundProducer'));
    }

}
