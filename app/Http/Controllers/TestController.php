<?php

namespace App\Http\Controllers;

use App\Models\Product;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TestController extends Controller
{

    public function index()
    {

        dd(round(1.535,2));
        $storeId = 4;
        //приход - расход
        // поступления +  перемешение с склада + возврат от клиента  - продажа  - возврат поставщику  - перемешеие на склад = остаток


        $products  = Product::whereDate('created_at','>=',now()->subDay())->count();


        return $products;

    }
}
