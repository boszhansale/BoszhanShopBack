<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    public function index()
    {
        $brands = Brand::leftJoin('categories', 'categories.brand_id', 'brands.id')
            ->selectRaw('brands.*,COUNT(categories.id) as category_count')
            ->groupBy('brands.id')
            ->get();

        return view('admin.brand.index', compact('brands'));
    }

    public function create()
    {
        return view('admin.brand.create');
    }

    public function store(Request $request)
    {
        $brand = new Brand();
        $brand->name = $request->get('name');
        $brand->save();

        return redirect()->route('admin.brand.index');
    }

    public function edit(Brand $brand)
    {
        return view('admin.brand.edit', compact('brand'));
    }

    public function update(Request $request, Brand $brand)
    {
        $brand->name = $request->get('name');
        $brand->save();

        return redirect()->route('admin.brand.index');
    }

    public function delete(Brand $brand)
    {
        $brand->delete();

        return redirect()->back();
    }
}
