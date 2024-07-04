<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\AuthLoginRequest;
use App\Http\Requests\Api\UserUpdateRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function profile()
    {
        return response()->json(new UserResource(Auth::user()));
    }
    public function update(UserUpdateRequest $request)
    {
        $user = Auth::user()->update($request->validated());

        return response()->json(new UserResource($user));
    }
    public function cashiers(Request $request)
    {
        $cashiers = User::query()
            ->where('role', 'cashier')
            ->where('status',1)
            ->with(['store','stores.store'])
            ->get();

        return response()->json($cashiers);
    }
    public function changeStore(Request $request)
    {
        try {
            if (Auth::user()->role != 'moderator') throw new \Exception("у вас нет доступа");

            $user = User::find($request->get('user_id'));

            if (!$user) throw new \Exception("не найден");

            $storeId = $request->get('store_id');

            $userStore = $user->stores()->where('store_id',$storeId)->first();
            if (!$userStore) throw new \Exception("нет доступа ТТ");


            $user->store_id = $request->store_id;
            $user->webkassa_login = $userStore->webkassa_login;
            $user->webkassa_password = $userStore->webkassa_password;
            $user->webkassa_cash_box_id = $userStore->webkassa_cash_box_id;

            $user->webkassa_token = null;
            $user->webkassa_login_at = null;
            $user->save();
            $user->tokens()->delete();
            DB::commit();
            return response(['message' => 'success'],200);


        }catch (\Exception $exception){
            DB::rollBack();
            return response()->json($exception->getMessage(),404);
        }
    }


}
