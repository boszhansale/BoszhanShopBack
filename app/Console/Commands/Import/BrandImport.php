<?php

namespace App\Console\Commands\Import;

use App\Models\Brand;
use App\Models\Role;
use Illuminate\Console\Command;

class BrandImport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:brand';

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
        foreach (\DB::connection('boszhan')->table('brands')->get() as $brand) {
            Brand::updateOrCreate(
                ['id' => $brand->id],
                ['id' => $brand->id,'name' => $brand->name,'enabled' => $brand->enabled,'sort_position' => $brand->sort_position]
            );
        }
    }
}
