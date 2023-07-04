<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Counteragent;
use App\Models\Order;
use App\Models\Product;
use App\Models\Store;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\View\View;

class MainController extends Controller
{
    public function index(): View
    {


        return view('admin.main');
    }
}
