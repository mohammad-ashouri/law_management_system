<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class DatabaseBackup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'database:backup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create copy of mysql dump for existing database.';

    /**
     * Execute the console command.
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $filename = "backup-" . Carbon::now()->format('Y-m-d-H-i-s') . ".sql";
        $table = new \App\Models\DatabaseBackup();
        $folderName = md5(rand(5648, 10005484));
        // Create backup folder and set permission if not exist.
        $storageAt = storage_path() . "/app/public/backup/" . $folderName . '/';
        $table->filename = $folderName . '/' . $filename;
        $table->creator = session('id');
        $table->save();
        if (!File::exists($storageAt)) {
            File::makeDirectory($storageAt, 0775, true, true);
        }
        $command = "" . env('DB_DUMP_PATH', 'mysqldump') . " --user=" . env('DB_USERNAME') . " --password=" . env('DB_PASSWORD') . " --host=" . env('DB_HOST') . " " . env('DB_DATABASE') . " > " . $storageAt . $filename;
        $returnVar = NULL;
        $output = NULL;
        exec($command, $output, $returnVar);
    }
}
