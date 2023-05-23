<?php

namespace App\Console\Commands\Import;

use App\Models\Category;
use App\Models\Role;
use Illuminate\Console\Command;

class CategoryImport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:category';

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
        foreach (\DB::connection('boszhan')->table('categories')->get() as $category) {
            Category::updateOrCreate(
                ['id' => $category->id],
                ['id' => $category->id,'name' => $category->name,'brand_id' => $category->brand_id,'deleted_at' => $category->deleted_at,'enabled' => $category->enabled]
            );
        }
    }
}
