<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreStoreRequest;
use App\Models\Counteragent;
use App\Models\CounteragentUser;
use App\Models\Store;
use App\Models\StoreSalesrep;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StoreController extends Controller
{
    public function index(Request $request)
    {
        $driver_id = $request->get('driver_id');
        $salesrep_id = $request->get('salesrep_id');
        $counteragent_id = $request->get('counteragent_id');
        return view('admin.store.index', compact('driver_id', 'salesrep_id', 'counteragent_id'));
    }

    public function create()
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
        $counteragents = Counteragent::orderBy('name')->get();

        return view('admin.store.create', compact('salesreps', 'drivers', 'counteragents'));
    }

    public function store(StoreStoreRequest $request): RedirectResponse
    {
        $store = new Store();
        $store->name = $request->get('name');
        $store->id_sell = $request->get('id_sell');
        $store->id_edi = $request->get('id_edi');
        $store->phone = $request->get('phone');
        $store->bin = $request->get('bin');
        $store->salesrep_id = $request->get('salesrep_id');
        $store->driver_id = $request->get('driver_id');
        $store->counteragent_id = $request->get('counteragent_id');
        $store->district_id = $request->get('district_id');
        $store->address = $request->get('address');
        $store->lat = $request->get('lat');
        $store->lng = $request->get('lng');
        $store->discount = $request->get('discount');
        $store->enabled = $request->has('enabled');
        $store->save();

        return redirect()->route('admin.store.index');
    }

    public function edit(Store $store)
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

        $counteragents = Counteragent::all();
        return view('admin.store.edit', compact('salesreps', 'drivers', 'store', 'counteragents'));
    }

    public function update(Request $request, Store $store)
    {
        $store->name = $request->get('name');
        $store->id_sell = $request->get('id_sell');
        $store->id_edi = $request->get('id_edi');
        $store->phone = $request->get('phone');
        $store->bin = $request->get('bin');
        $store->salesrep_id = $request->get('salesrep_id');

        $store->driver_id = $request->get('driver_id');

        $store->counteragent_id = $request->get('counteragent_id');
        $store->district_id = $request->get('district_id');
        $store->address = $request->get('address');
        $store->lat = $request->get('lat');
        $store->lng = $request->get('lng');
        $store->discount = $request->get('discount');
        $store->enabled = $request->has('enabled');
        $store->save();

        if ($request->has('salesreps')) {
            foreach ($request->get('salesreps') as $userId) {
                $store->salesreps()->updateOrCreate(
                    ['salesrep_id' => $userId, 'store_id' => $store->id],
                    ['salesrep_id' => $userId, 'store_id' => $store->id],
                );
            }
        } else {
            $store->salesreps()->delete();
        }

        return redirect()->back();
    }

    public function show(Request $request, Store $store)
    {
//        $orders = Order::limit(400)->offset(1600)->get();
//        foreach ($orders as $order) {
//            $store = $order->store;
//            $counteragent = $store->counteragent;
//            $priceType = $counteragent ? $counteragent->priceType: PriceType::find(1);
//
//            $discount = $counteragent ? $counteragent->discount: 0;
//            $discount = $discount == 0 ? $store->discount : $discount;
//
//            foreach ($order->baskets as $value) {
//                $product = Product::find($value['product_id']);
//
//                if (!$product) continue;
//                $productPriceType = $product->prices()->where('price_type_id',$priceType->id)->first();
//                $discount = $discount == 0 ?  $product->discount : $discount;
//
//                if (!$productPriceType) continue;
//                if ($value['type'] == 1){
//                    $basket = Basket::join('orders','orders.id','baskets.order_id')
//                        ->where('orders.salesrep_id',$order->salesrep_id)
//                        ->where('baskets.type',0)
//                        ->where('orders.store_id',$order->store_id)
//                        ->where('baskets.product_id',$product->id)
//                        ->latest('baskets.id')
//                        ->first();
//                    if ($basket){
//                        $value['price'] = $basket->price;
//                    }else {
//                        $value['price'] = $this->discount($productPriceType->price,$discount);
//                    }
//                }else{
//                    $discount = $discount == 0 ?  $product->discount : $discount;
//                    $value['price'] = $this->discount($productPriceType->price,$discount);
//                }
//                $value['all_price'] = $value['count'] * $value['price'];
//
//                $value->save();
//            }
//        }

        $orders = $store->orders()->with(['salesrep', 'driver'])
            ->when($request->has('start_date'), function ($query) {
                return $query->whereDate('orders.delivery_date', '>=', $this->start_date);
            })
            ->when($request->has('end_date'), function ($query) {
                return $query->whereDate('orders.delivery_date', '<=', $this->end_date);
            })
            ->latest()
            ->paginate(50);

        $purchasePrices = $store->orders()->sum('purchase_price');
        $returnPrices = $store->orders()->sum('return_price');

        return view('admin.store.show', compact('store', 'orders', 'purchasePrices', 'returnPrices'));
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

    public function order(Store $store)
    {
        return response()->view('admin.store.order', compact('store'));
    }

    public function salesrepMove(): View
    {
        return view('admin.store.salesrep_move');
    }

    public function salesrepMoving(Request $request): RedirectResponse
    {
        Store::whereSalesrepId($request->get('from_salesrep_id'))->update(
            ['salesrep_id' => $request->get('to_salesrep_id')]
        );
        StoreSalesrep::whereSalesrepId($request->get('from_salesrep_id'))->update(
            ['salesrep_id' => $request->get('to_salesrep_id')]
        );

        CounteragentUser::where('user_id', $request->get('from_salesrep_id'))
            ->update(
                ['user_id' => $request->get('to_salesrep_id')]
            );


        return to_route('admin.user.show', $request->get('to_salesrep_id'));
    }

    public function driverMove(): View
    {
        return view('admin.store.driver_move');
    }

    public function driverMoving(Request $request): RedirectResponse
    {
        Store::whereDriverId($request->get('from_driver_id'))->update(
            ['driver_id' => $request->get('to_driver_id')]
        );


        return to_route('admin.user.show', $request->get('to_driver_id'));
    }

    public function position(Request $request, User $user): View
    {

        $positions = $user->stores()
            ->when($request->has('date'), function ($q) {
                return $q->whereDate('created_at', \request('date'));
            })
            ->whereNotNull(['lat', 'lng'])
            ->selectRaw("REPLACE(stores.name,'\"',' ') as name,stores.id,stores.lat,stores.lng,  TIME(created_at) as time")
            ->get();

        return view('admin.store.position', compact('user', 'positions'));
    }


    protected function discount($price, $discount): float|int
    {
        $discountPrice = ($price / 100) * $discount;

        return $price - $discountPrice;
    }
}
