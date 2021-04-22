<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Helpers\AvatarGenerator;
use App\Models\User;

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

        $userList = User::with('organization')
            ->whereNull('avatar')
            ->orderBy('id', 'desc')
            ->get();

        foreach ($userList as $user) {
            $fullName = $user->full_name;
            $this->info($fullName);
            AvatarGenerator::autoGenerateAvatar($user);
        }
    }
}
