<?php

namespace App\Imports;

use App\Models\PromoCode;
use DB;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Session;

class PromoCodeImport implements ToCollection, WithStartRow
{
    /**
    * @param Collection $collection
    */

// ...

    public function collection(Collection $collection)
    {
        $messages = [];

        DB::transaction(function () use ($collection, &$messages) {
            foreach ($collection as $item) {
                if (!$item[2]) continue;

                $phone = substr($item[2], -10);
                $promoCode = PromoCode::where('phone', $phone)->first();

                if (!$promoCode) {
                    PromoCode::create([
                        'name' => $item[1],
                        'phone' => $phone,
                        'discount' => 10,
                        'start' => now(),
                        'end' => now()->addHours(48),
                    ]);

                    // Добавим информационное сообщение о создании нового промокода
                    $messages[] = "Промокод для номера телефона $phone успешно создан.";
                } else {
                    // Если промокод уже существует, добавим сообщение об этом
                    $messages[] = "Промокод для номера телефона $phone уже существует.";
                }
            }
        });

        // Сохраняем сообщения во флеш-сессии
        Session::flash('info_messages', $messages);

        return redirect()->route('admin.promo-code.index');
    }


    public function startRow(): int
    {
        return 2;
    }
}
