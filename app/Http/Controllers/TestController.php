<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use App\Models\Moving;
use App\Models\Product;
use App\Models\User;
use App\Services\AmountWord;
use App\Services\WebKassa\WebKassaService;
use Barryvdh\DomPDF\Facade\Pdf;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TestController extends Controller
{

    public function index()
    {


//        $data = WebKassaService::XReport(User::find(2503));
//        \Cache::put('2503',$data);

        $data= \Cache::get('2503');
//        dd($data);
//        $inventory = Inventory::latest()->first();
        return view('pdf.x-report',compact('data'));

//        $moving = Moving::findOrFail(10);
//        return view('pdf.moving',compact('moving'));

//        $pdf = Pdf::loadView('pdf.inventory',compact('inventory'))->setPaper('a4', 'landscape');;
//        $pdf = Pdf::loadView('pdf.moving',compact('moving'));

//        return $pdf->download('inv.pdf');
    }
}
