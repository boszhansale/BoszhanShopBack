<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class DatabaseBackup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:backup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Do backups/dump';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    protected $process;

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        $this->clear();

        $path = storage_path('app/public/database/');
        $name = sprintf('/home/dev/index/backups/sql/' . 'backup_%s.sql', now()->format('YmdHis'));
        //mysql
        $this->process = Process::fromShellCommandline(sprintf(
            'mysqldump -u%s -p%s %s > %s',
            config('database.connections.mysql.username'),
            config('database.connections.mysql.password'),
            config('database.connections.mysql.database'),
            ($name)
        ));

        try {
            $this->info('The backup has been started');
            $this->process->mustRun();
            $this->info('The backup has been proceed successfully.');
            logger('test cron');
        } catch (ProcessFailedException $exception) {
            logger()->error('Backup exception', compact('exception'));
            $this->error('The backup process has been failed.');
        }
    }

    protected function clear()
    {
        $files = Storage::disk('ftpBackup')->files();
        $date = (int)Carbon::now()->subDays(3)->format('Ymd');
        foreach ($files as $fileName) {

            $d = (int)substr($fileName, 7, 8);
            if ($d <= $date) {
                dump('delete: ' . $fileName);

                Storage::disk('ftpBackup')->delete($fileName);
            }

        }
    }
}
