<?php

namespace App\Http\Livewire\Admin;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\PromoCode;
use Livewire\Component;
use Livewire\WithPagination;

class PromoCodeIndex extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $search;

    public function render()
    {
        return view('admin.promo-code.index_live', [
            'promoCodes' => PromoCode::query()
                ->where(function ($q){
                    $q->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('phone', 'like', '%' . $this->search . '%');
                })
                ->latest()
                ->paginate(40),
        ]);
    }
}
