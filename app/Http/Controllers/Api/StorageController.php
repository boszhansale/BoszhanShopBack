<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Storage;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StorageController extends Controller
{
    public function index(Request $request)
    {

        $stores = Storage::query()
            ->orderBy('storages.name')
            ->select('storages.*')
            ->get();


        return response()->json($stores);
    }
}
