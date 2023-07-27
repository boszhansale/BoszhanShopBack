<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\WebkassaMoneyOperationRequest;
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

            return  response()->json([
                'message' => $e->getMessage()
            ],400);
        }
    }
    public function moneyOperation(WebkassaMoneyOperationRequest $request)
    {
        $user= \Auth::user();
        try {
            return response()->json([
                'sum' => WebKassaService::moneyOperation($user,$request->get('operation_type'),$request->get('sum'))
            ]);
        } catch (\Exception $e) {

            return  response()->json([
                'message' => $e->getMessage()
            ],400);
        }
    }
    public function ZReport()
    {
        $user= \Auth::user();
        try {
            return response()->json(WebKassaService::ZReport($user));
        } catch (\Exception $e) {

            return  response()->json([
                'message' => $e->getMessage()
            ],400);
        }
    }
    public function XReport()
    {
        $user= \Auth::user();
        try {
            return response()->json(WebKassaService::XReport($user));
        } catch (\Exception $e) {
            return  response()->json([
                'message' => $e->getMessage()
            ],400);
        }
    }
}
