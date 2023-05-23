<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StoreController extends Controller
{
    public function index(Request $request)
    {

        $stores = Store::query()
            ->leftJoin('store_salesreps', 'store_salesreps.store_id', 'stores.id')
            ->where(function ($q) {
                return $q->where('stores.salesrep_id', Auth::id())->orWhere('store_salesreps.salesrep_id', Auth::id());
            })
            ->when($request->has('counteragent'), function ($query) {
                if (\request('counteragent') == 1) {
                    return $query->whereNotNull('counteragent_id');
                } else {
                    return $query->whereNull('counteragent_id');
                }
            })
            ->when($request->has('counteragent_id'), function ($q) {
                return $q->where('counteragent_id', \request('counteragent_id'));
            })
            ->groupBy('stores.id')
            ->orderBy('stores.name')
            ->with(['salesrep', 'counteragent'])
            ->select('stores.*')
            ->get();


        return response()->json($stores);
    }

    public function store(StoreStoreRequest $request)
    {
        $store = Auth::user()->stores()->create($request->validated());

        $store->id_sell = 300000000000000 + $store->id;
        $store->save();

        return response()->json(Store::with(['salesrep', 'counteragent'])->find($store->id));
    }

    public function update(StoreUpdateRequest $request, Store $store)
    {
        $store->update($request->validated());

        return response()->json($store);
    }
}
