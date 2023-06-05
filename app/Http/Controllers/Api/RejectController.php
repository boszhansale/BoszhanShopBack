<?php

namespace App\Http\Controllers\Api;

use App\Actions\RejectStoreAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\RejectIndexRequest;
use App\Http\Requests\Api\RejectStoreRequest;
use App\Models\Order;
use App\Models\Receipt;
use App\Models\ReceiptProduct;
use App\Models\Reject;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
//Списание
class RejectController extends Controller
{
    public function index(RejectIndexRequest $request)
    {
        $rejects = Reject::query()
            ->where('rejects.user_id',Auth::id())
            ->with(['products','products.product','store'])
            ->when($request->has('date_from'),function ($q){
                $q->whereDate('created_at','>=',request('date_from'));
            })
            ->when($request->has('date_to'),function ($q){
                $q->whereDate('created_at','<=',request('date_to'));
            })
            ->get();
        return response()->json($rejects);
    }

    public function history(RejectIndexRequest $request)
    {
        $rejects = Reject::query()
            ->where('rejects.user_id',Auth::id())
            ->with(['products','products.product','store'])
            ->when($request->has('date_from'),function ($q){
                $q->whereDate('created_at','>=',request('date_from'));
            })
            ->when($request->has('date_to'),function ($q){
                $q->whereDate('created_at','<=',request('date_to'));
            })
            ->get();
        return response()->json($rejects);
    }

    public function store(RejectStoreRequest $request,RejectStoreAction $rejectStoreAction)
    {
        $data = [];
        $data['storage_id'] = Auth::user()->storage_id;
        $data['store_id'] = Auth::user()->store_id;
        $data['organization_id'] = Auth::user()->organization_id;
        try {
            return response()->json( $rejectStoreAction->execute(array_merge($request->validated(),$data)));

        }catch (\Exception $exception)
        {
           return response()->json(['message' => $exception->getMessage()],400);
        }


    }

    public function delete(Reject $reject)
    {
        $reject->delete();
        return response()->json($reject);
    }


}
