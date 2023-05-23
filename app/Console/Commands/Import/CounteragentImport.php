<?php

namespace App\Console\Commands\Import;

use App\Models\Category;
use App\Models\Counteragent;
use App\Models\Role;
use Illuminate\Console\Command;

class CounteragentImport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:counteragent';

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
        foreach (\DB::connection('boszhan')->table('counteragents')->get() as $counteragent) {
            Counteragent::updateOrCreate(
                ['id' => $counteragent->id],
                [
                    'id' => $counteragent->id,
                    'name' => $counteragent->name,
                    'id_1c' => $counteragent->id_1c,
                    'bin' => $counteragent->bin,
                    'iik' => $counteragent->iik,
                    'bik' => $counteragent->bik,
                    'payment_type' => $counteragent->payment_type_id,
                    'price_type_id' => $counteragent->price_type_id,
                    'discount' => $counteragent->discount,
                    'enabled' => $counteragent->enabled,
                    'created_at' => $counteragent->created_at,
                    'deleted_at' => $counteragent->deleted_at,
                ]
            );
        }
    }
}
