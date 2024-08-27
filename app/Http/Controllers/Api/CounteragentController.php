<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Counteragent;
use Illuminate\Http\JsonResponse;

class CounteragentController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(Counteragent::with('priceType')->get());
    }

    public function all(): JsonResponse
    {
        return response()->json(Counteragent::with('priceType')->get());
    }
}
