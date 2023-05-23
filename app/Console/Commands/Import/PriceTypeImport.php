<?php

namespace App\Console\Commands\Import;

use App\Models\Category;
use App\Models\Counteragent;
use App\Models\PriceType;
use App\Models\Role;
use Illuminate\Console\Command;

class PriceTypeImport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:priceType';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        foreach (\DB::connection('boszhan')->table('price_types')->get() as $priceType) {
            PriceType::updateOrCreate(
                ['id' => $priceType->id],
                [
                    'id' => $priceType->id,
                    'name' => $priceType->name,
                    'description' => $priceType->description,
                    'deleted_at' => $priceType->deleted_at,
                ]
            );
        }
    }
}
