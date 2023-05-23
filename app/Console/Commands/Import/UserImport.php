<?php

namespace App\Console\Commands\Import;

use App\Models\Role;
use App\Models\User;
use Illuminate\Console\Command;

class UserImport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:user';

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
        foreach (\DB::connection('boszhan')->table('users')->get() as $user) {
            User::updateOrCreate(
                ['id' => $user->id],
                [
                    'id' => $user->id,
                    'name' => $user->name,
                    'phone' => $user->phone,
                    'password' => $user->password,
                    'login' => $user->login,
                    'id_1c' => $user->id_1c,
                    'device_token' => $user->device_token,
                    'created_at' => $user->created_at,
                    'lat' => $user->lat,
                    'lng' => $user->lng,
                ]
            );
        }
    }
}
