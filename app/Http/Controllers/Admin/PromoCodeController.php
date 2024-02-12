<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\OrderManyUpdateRequest;
use App\Http\Requests\Admin\OrderUpdateRequest;
use App\Http\Requests\Admin\PromoCodeImportRequest;
use App\Http\Requests\Admin\PromoCodeStoreRequest;
use App\Http\Requests\Admin\PromoCodeUpdateRequest;
use App\Models\PromoCode;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\View\View;
use Log;
use Maatwebsite\Excel\Facades\Excel;

class PromoCodeController extends Controller
{
    public function index(Request $request)
    {
        return view('admin.promo-code.index');
    }

    public function create(): View
    {
        return view('admin.promo-code.create');
    }

    public function store(PromoCodeStoreRequest $request): \Illuminate\Http\RedirectResponse
    {
        PromoCode::create($request->validated());

        return redirect()->route('admin.promo-code.index');
    }

    public function edit(PromoCode $promoCode): View
    {

        return view('admin.promo-code.edit', compact('promoCode'));
    }

    public function update(PromoCodeUpdateRequest $request, PromoCode $promoCode): \Illuminate\Http\RedirectResponse
    {
        $promoCode->update($request->validated());

        return redirect()->route('admin.promo-code.index');
    }

    public function delete(PromoCode $promoCode)
    {
        $promoCode->delete();

        return redirect()->back();
    }

    public function import()
    {
        return view('admin.promo-code.import');
    }
    public function importStore(PromoCodeImportRequest $request)
    {
        try {
            Excel::import(new \App\Imports\PromoCodeImport(), $request->file('file'));

            // Успешный импорт
            return redirect()->route('admin.promo-code.index')->with('success', 'Промокоды успешно импортированы.');
        } catch (\Exception $e) {
            // Ошибка импорта
            dd($e);
            return redirect()->route('admin.promo-code.index')->with('error', 'Произошла ошибка при импорте промокодов.');
        }
    }


}
