<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\WebkassaMoneyOperationRequest;
use App\Models\Report;
use App\Models\User;
use App\Services\WebKassa\WebKassaService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WebkassaController extends Controller
{
    public function getToken()
    {
        $user = \Auth::user();

        try {
            return response()->json([
                'token' => WebKassaService::authorize($user)
            ]);
        } catch (\Exception $e) {

            return response()->json([
                'message' => $e->getMessage()
            ], 400);
        }
    }

    public function moneyOperation(WebkassaMoneyOperationRequest $request)
    {
        $user = \Auth::user();
        try {
            return response()->json([
                'sum' => WebKassaService::moneyOperation($user, $request->get('operation_type'), $request->get('sum'))
            ]);
        } catch (\Exception $e) {

            return response()->json([
                'message' => $e->getMessage()
            ], 400);
        }
    }

    public function ZReport()
    {
        $user = \Auth::user();
        try {
            return response()->json(WebKassaService::ZReport($user));
        } catch (\Exception $e) {
            $user->webkassa_token = null;
            $user->webkassa_login_at = null;
            $user->save();
            return response()->view('message', ['message' => $e->getMessage()]);
        }
    }

    public function XReport()
    {
        $user = \Auth::user();
        try {
            $data = WebKassaService::XReport($user);
            Report::create([
                'user_id' => $user->id,
                'store_id' => $user->store_id,
                'name' => 'x-report',
                'body' => $data
            ]);
            return response()->json($data);
        } catch (\Exception $e) {
            $user->webkassa_token = null;
            $user->webkassa_login_at = null;
            $user->save();
            return response()->view('message', ['message' => $e->getMessage()]);
        }
    }

    public function ZReportPrint(User $user)
    {
        try {
            $data = WebKassaService::ZReport($user);
            Report::create([
                'user_id' => $user->id,
                'store_id' => $user->store_id,
                'name' => 'z-report',
                'body' => $data
            ]);
            return view('pdf.z-report', compact('data'));
        } catch (\Exception $e) {
            $user->webkassa_token = null;
            $user->webkassa_login_at = null;
            $user->save();
            return response()->view('message', ['message' => $e->getMessage()]);
        }
    }

    public function XReportPrint(User $user)
    {
        try {
            $data = WebKassaService::XReport($user);

//            Report::create([
//                'user_id' => $user->id,
//                'store_id' => $user->store_id,
//                'name' => 'x-report',
//                'body' => $data
//            ]);
            return view('pdf.x-report', compact('data'));
        } catch (\Exception $e) {

            $user->webkassa_token = null;
            $user->webkassa_login_at = null;
            $user->save();

            return response()->view('message', ['message' => $e->getMessage()]);
        }
    }
}
