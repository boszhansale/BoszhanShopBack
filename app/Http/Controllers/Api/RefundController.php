<?php

namespace App\Http\Controllers\Api;

use App\Actions\RefundStoreAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\AuthLoginRequest;
use App\Http\Requests\Api\OrderCheckRequest;
use App\Http\Requests\Api\RefundIndexRequest;
use App\Http\Requests\Api\RefundStoreRequest;
use App\Http\Requests\Api\RefundUpdateRequest;
use App\Http\Requests\Api\UserUpdateRequest;
use App\Http\Resources\UserResource;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Refund;
use App\Models\RefundProduct;
use App\Models\PriceType;
use App\Models\Product;
use App\Models\User;
use App\Models\WebkassaCheck;
use App\Services\WebKassa\WebKassaService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

//Возврат от покупателя
class RefundController extends Controller
{
    public function index(RefundIndexRequest $request)
    {
        $refunds = Refund::query()
            ->where('refunds.store_id',Auth::user()->store_id)
            ->with(['products','products.product','products.reasonRefund','store'])
            ->latest()
            ->when($request->has('date_from'),function ($q){
                $q->whereDate('created_at','>=',request('date_from'));
            })
            ->when($request->has('date_to'),function ($q){
                $q->whereDate('created_at','<=',request('date_to'));
            })
             ->get();
        return response()->json($refunds);
    }

    public function history(RefundIndexRequest $request)
    {
        $refunds = Refund::query()
            ->where('refunds.store_id',Auth::user()->store_id)
            ->with(['products','products.product','products.reasonRefund','store'])
            ->latest()
            ->when($request->has('date_from'),function ($q){
                $q->whereDate('created_at','>=',request('date_from'));
            })
            ->when($request->has('date_to'),function ($q){
                $q->whereDate('created_at','<=',request('date_to'));
            })

            ->get();
        return response()->json($refunds);
    }

    public function store(RefundStoreRequest $request,RefundStoreAction $refundStoreAction)
    {
        $data = [];
        $order = Order::findOrFail($request->get('order_id'));
        $data['storage_id'] = Auth::user()->storage_id;
        $data['store_id'] = Auth::user()->store_id;
        $data['organization_id'] = Auth::user()->organization_id;
        $data['ticket_print_url'] = null;
        DB::beginTransaction();
        try {

           $refund =  $refundStoreAction->execute(array_merge($request->validated(),$data),$order);
//            WebKassaService::checkRefund($refund);
            DB::commit();
            return response()->json($refund);
        }catch (\Exception $exception)
        {
            DB::rollBack();
            return response()->json(['message' => $exception->getMessage()],400);
        }

    }

    public function delete(Refund $refund)
    {
        $refund->delete();
        return response()->json($refund);
    }

    public function check($refundId,OrderCheckRequest $request)
    {

        $refund = Refund::find($refundId);
        if (!$refund){
            return response()->json(['message' =>'ошибка, попробуйте заново создать заявку'],400);
        }

        try {
            $refund->payments = $request->get('payments');
            $refund->save();
            $data =  WebKassaService::checkRefund($refund,$request->get('payments'));
            return response()->json($data);
        }catch (\Exception $exception){
            $refund->delete();
            return response()->json(['message' => $exception->getMessage()],400);
        }
    }

    public function printCheck(Refund $refund)
    {
        try {


            $check = WebkassaCheck::where('refund_id',$refund->id)->whereNotNull('check_number')->latest()->first();
            if (!$check)
            {
                throw new Exception('чек не найден');
            }

            $data =  WebKassaService::printFormat(Auth::user(),$check->number);
            return response()->json($data);
        }catch (\Exception $exception){

            return response()->json(['message' => $exception->getMessage()],400);
        }
    }

    public function printCheckFormat(Refund $refund)
    {
        try {
            $check = WebkassaCheck::where('refund_id',$refund->id)->whereNotNull('check_number')->latest()->first();


            if (!$check)
            {
                throw new Exception('чек не найден');
            }

            $data =  WebKassaService::printFormat($refund->user,$check->number);

            return response()->view('pdf.check',compact('data'));
        }catch (\Exception $exception){

            return response()->json(['message' => $exception->getMessage()],400);
        }
    }


}
