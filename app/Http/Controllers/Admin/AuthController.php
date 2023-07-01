<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\AuthRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
class AuthController extends Controller
{
    public function login()
    {
        if (Auth::check()) {
            return to_route('admin.main');
        }

        return view('admin.login');
    }

    public function auth(AuthRequest $request)
    {
        $user = User::whereLogin($request->get('login'))->first();

        if (!Hash::check($request->get('password'), $user->password)) {
            return back()->withErrors('Неправильный пароль');
        }
        Auth::login($user, 1);
        return to_route('admin.login');
    }

    public function logout()
    {
        Auth::logout();

        return to_route('admin.login');
    }
}
