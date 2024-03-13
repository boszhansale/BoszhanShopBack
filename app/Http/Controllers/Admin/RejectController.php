<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\OrderManyUpdateRequest;
use App\Http\Requests\Admin\OrderUpdateRequest;
use App\Models\Reject;
use App\Models\RejectProduct;
use App\Models\User;
use DB;
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

    public function edit(Reject $reject): View
    {
        return view('admin.reject.edit', compact('reject'));
    }

    public function update(Request $request, Reject $reject)
    {

        DB::beginTransaction();
        try {
            foreach ($request->get('products') as $productId => $item) {
                $rejectProduct = RejectProduct::findOrFail($productId);
                $rejectProduct->count = $item['count'];
                $rejectProduct->all_price = $rejectProduct->price * $item['count'];
                $rejectProduct->save();
            }
            $reject->total_price = $reject->products()->sum('all_price');
            $reject->save();
            DB::commit();

            return redirect()->route('admin.reject.show', $reject->id)->with('success', 'Отгрузка успешно обновлена');

        } catch (\Exception $e) {
            dd($e->getMessage());
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage());
        }

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
