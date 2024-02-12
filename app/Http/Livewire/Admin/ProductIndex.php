<?php

namespace App\Http\Livewire\Admin;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;

class ProductIndex extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $search;

    public $brand_id = 'all';

    public $category_id = 'all';

    public $categories = [];

    public function mount()
    {
        $this->categories = Category::orderBy('name')
            ->where('enabled', 1)
            ->get();
    }

    public function render()
    {
        return view('admin.product.index_live', [
            'brands' => Brand::all(),
            'categories' => $this->categories,
            'products' => Product::select('products.*')
                ->when($this->search, function ($q) {
                    return $q->where(function ($qq) {
                        return $qq->where('products.name', 'LIKE', '%' . $this->search . '%')
                            ->orWhere('products.article', 'LIKE', '%' . $this->search . '%');
                    });
                })
                ->join('categories', 'categories.id', 'products.category_id')
                ->when($this->brand_id != 'all', function ($q) {
                    return $q->where('categories.brand_id', $this->brand_id);
                })
                ->when($this->category_id != 'all', function ($q) {
                    return $q->where('categories.id', $this->category_id);
                })
                ->with('category')
                ->orderBy('products.article')
                ->paginate(50),
        ]);
    }

    public function updatedBrandId($value)
    {
        $this->categories = Category::whereBrandId($value)->get();
    }
}
