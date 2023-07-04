<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\OrderManyUpdateRequest;
use App\Http\Requests\Admin\OrderUpdateRequest;
use App\Models\Moving;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;

class MovingController extends Controller
{
    public function index(Request $request)
    {
        $storeId = $request->get('store_id');
        $storageId = $request->get('storage_id');
        $userId = $request->get('user_id');

        return view('admin.moving.index', compact( 'storeId', 'userId','storageId'));
    }

    public function edit(Moving $moving): View
    {

        return view('admin.moving.edit', compact('moving'));
    }

    public function update(OrderUpdateRequest $request, Moving $moving)
    {
        $moving->update($request->validated());

        return redirect()->back();
    }

    public function show($orderId)
    {
        $moving = Moving::withTrashed()->find($orderId);

        return view('admin.moving.show', compact('moving'));
    }

    public function delete(Moving $moving)
    {
        $moving->delete();

        return redirect()->back();
    }

    public function remove(Moving $moving)
    {
        $moving->removed_at = now();
        $moving->save();
        return redirect()->back();
    }

    public function recover($id)
    {

        $moving = Moving::where('id', $id)->withTrashed()->first();
        $moving->removed_at = null;
        $moving->deleted_at = null;
        $moving->save();

        return redirect()->back();
    }

    public function history(Moving $moving)
    {
        return \view('admin.moving.history', compact('moving'));
    }

}
