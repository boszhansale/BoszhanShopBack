<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Store;
use App\Models\UserStore;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StoreController extends Controller
{
    public function index(Request $request)
    {

        $stores = Store::query()
            ->join('user_stores', 'user_stores.store_id', '=', 'stores.id')
            ->where('user_stores.user_id', Auth::id())
            ->get();

        return response()->json($stores);
    }

    public function save(Request $request)
    {
        DB::beginTransaction();

        try {
            $userStore = UserStore::query()
                ->where('user_id', Auth::id())
                ->where('store_id', $request->store_id)
                ->latest()
                ->first();
            if (!$userStore){
                throw new \Exception("not found store");
            }

            $user = Auth::user();
            $user->store_id = $request->store_id;
            $user->webkassa_login = $userStore->webkassa_login;
            $user->webkassa_token = $userStore->webkassa_token;
            $user->webkassa_password = $userStore->webkassa_password;
            $user->webkassa_login_at = $userStore->webkassa_login_at;
            $user->webkassa_cash_box_id = $userStore->webkassa_cash_box_id;
            $user->save();

            DB::commit();
            return response(['message' => 'success'],200);


        } catch (\Exception $exception) {
            DB::rollBack();
            return response(['message' => $exception->getMessage()],400);
        } catch (\Throwable $e) {
            return response(['message' => $e->getMessage()],500);
        }


    }
}
