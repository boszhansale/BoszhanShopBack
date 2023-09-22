<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(Brand $brand)
    {
        $categories = $brand->categories;

        return  view('admin.category.index', compact('categories', 'brand'));
    }

    public function create(Brand $brand)
    {
        return  view('admin.category.create', compact('brand'));
    }

    public function store(Request $request, Brand $brand)
    {
        $cat = new Category();
        $cat->name = $request->get('name');
        $cat->brand_id = $brand->id;
        $cat->enabled = $request->has('enabled');
        $cat->save();

        return redirect()->route('admin.category.index', $brand->id);
    }

    public function edit(Category $category)
    {
        return view('admin.category.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $category->name = $request->get('name');
        $category->enabled = $request->has('enabled');
        $category->save();

        return redirect()->route('admin.category.index', $category->brand_id);
    }

    public function delete(Category $category)
    {
        $brand_id = $category->brand_id;
        $category->delete();

        return redirect()->route('admin.category.index', $brand_id);
    }
}
