<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use App\Helpers\AvatarGenerator;
use App\Imports\VienamZoneImport;
use Maatwebsite\Excel\Facades\Excel;

/**
 * Generate avatar cho những user chưa có.
 */
class GenerateAvatar extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'avatar:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate avatar cho những user chưa có';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Lấy danh sách người dùng chưa có avatar...');

        /*
        $tmpFile = app(Downloader::class)->downloadFile();

        $this->info('Importing...');

        Excel::import(new VienamZoneImport(), $tmpFile);

        File::delete($tmpFile);

        $this->info('Completed');
        */
    }
}
