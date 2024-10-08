<?php

namespace App\Http\Controllers\Admin;

use App\Exports\Excel\UserOrderExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UserStoreRequest;
use App\Http\Requests\Admin\UserUpdateRequest;
use App\Models\Brand;
use App\Models\Counteragent;
use App\Models\CounteragentUser;
use App\Models\Counterparty;
use App\Models\DriverSalesrep;
use App\Models\Order;
use App\Models\PlanGroup;
use App\Models\PlanGroupUser;
use App\Models\Role;
use App\Models\Storage;
use App\Models\Store;
use App\Models\SupervisorSalesrep;
use App\Models\User;
use App\Models\UserStore;
use App\Models\WebkassaCashBox;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();

        return view('admin.user.index', compact('users'));
    }

    public function show(User $user)
    {

        return view('admin.user.show', compact('user'));
    }

    public function position(Request $request, User $user): View
    {
        $positions = $user->userPositions()
            ->when($request->has('date'), function ($q) {
                return $q->whereDate('created_at', \request('date'));
            }, function ($q) {
                return $q->whereDate('created_at', now());
            })
            ->selectRaw('user_positions.*, TIME(created_at) as time')
            ->get();

        return view('admin.user.position', compact('user', 'positions'));
    }

    public function order(Request $request, User $user, $role)
    {
        return view('admin.user.order', compact('user', 'role'));
    }

    public function create()
    {

        $stores = Store::orderBy('name')->get();
        $storages = Storage::orderBy('name')->get();
        $cashboxes = WebkassaCashBox::latest()->get();

        return view('admin.user.create', compact( 'stores','storages' , 'cashboxes'));
    }

    public function store(UserStoreRequest $request)
    {
        $user = User::create($request->validated());

        return redirect()->route('admin.user.index');
    }

    public function edit(User $user)
    {
        $stores = Store::orderBy('name')->whereNotNull('warehouse_in')->get();
        $storages = Storage::orderBy('name')->get();
        $userStores = [];
        foreach ($stores as $i => $store) {
            $userStores[$i]['store_id'] = $store->id;
            $userStores[$i]['store_name'] = $store->name;
            $userStores[$i]['user_store'] = UserStore::query()
                ->where('user_id', $user->id)
                ->where('store_id', $store->id)
                ->first();
        }

        $cashboxes = WebkassaCashBox::latest()->get();
        return view('admin.user.edit', compact('user', 'stores','storages','cashboxes','userStores'));
    }

    public function update(UserUpdateRequest $request, User $user)
    {
        $user->update($request->validated());

        if ($request->has('user_stores')){
            foreach ($request->get('user_stores') as $storeId => $value) {
                if ($value['webkassa_login'] AND $value['webkassa_password'] AND $value['webkassa_cash_box_id']){
                    UserStore::updateOrCreate([
                        'user_id' => $user->id,
                        'store_id' => $storeId,
                    ],[
                        'webkassa_login' => $value['webkassa_login'],
                        'webkassa_password' => $value['webkassa_password'],
                        'webkassa_cash_box_id' => $value['webkassa_cash_box_id'],

                    ]);
                }else{
                    UserStore::query()
                        ->where('user_id', $user->id)
                        ->where('store_id', $storeId)
                        ->delete();
                }
            }
        }
        

        return redirect()->back();
    }

    public function statusChange(User $user, $status)
    {
        $user->status = $status;
        $user->save();
    }

    public function delete(User $user)
    {
        $user->delete();

        return redirect()->back();
    }

    public function drivers()
    {
        return response()->view('admin.user.drivers');
    }

    public function salesreps()
    {
        $salesreps = User::where('role_id', 1)->where('status', 1)
            ->orderBy('name')->get();
        return response()->view('admin.user.salesreps', compact('salesreps'));
    }

    public function supervisors()
    {
        return response()->view('admin.user.supervisors');
    }

    public function statisticByOrderExcel(Request $request)
    {
        $ordersQuery = Order::query()
            ->select('orders.*')
            ->join('stores', 'stores.id', 'orders.store_id')
            ->when($request->has('from'), function ($q) {
                $q->whereDate('orders.created_at', '>=', \request('from'));
            })
            ->when($request->has('to'), function ($q) {
                $q->whereDate('orders.created_at', '<=', \request('to'));
            })
            ->whereNull('orders.removed_at');

        $users = User::query()
            ->whereIn('id', $request->get('users'))
            ->where('status', 1)
            ->where('role_id', 1)
            ->get();

        return Excel::download(new UserOrderExport($users, $ordersQuery), 'статистика.xlsx');

    }
}
