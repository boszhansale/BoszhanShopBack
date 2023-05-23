<?php

namespace App\Console\Commands\Import;

use App\Models\Role;
use Illuminate\Console\Command;

class RoleImport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:role';

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
        foreach (\DB::connection('boszhan')->table('roles')->get() as $role) {
            Role::updateOrCreate(['id' => $role->id],['id' => $role->id,'name' => $role->name]);
        }
    }
}
