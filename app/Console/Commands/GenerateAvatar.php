<?php

namespace App\Console\Commands;

use App\Helpers\AvatarGenerator;
use App\Models\User;
use Illuminate\Console\Command;

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
        $userList = User::whereNull('avatar')
            ->orderBy('id', 'desc')
            ->get();

        foreach ($userList as $user) {
            // AvatarGenerator::autoGenerateAvatar($user);
        }
        */

        // Giả sử tổng số bản ghi nhiều mà chúng ta cần duyệt tất cả các bản ghi
        // thì thay vì select tất cả các bản ghi một lần, có thể gây hết bộ nhớ,
        // chúng ta nên sử dụng chunkById để xử lý từng phần
        // Ngoài ra, cũng nên chỉ select các trường cần sử dụng
        // Làm thế thì khi update, các trường không được select có bị update thành null không?
        $count = 1;
        User::select(['id', 'full_name'])
            ->chunkById(10, function ($userList) use (&$count) {
                // Phải thêm ký tự & thì mới có thể thay đổi biến $count trong closure
                $this->info($count);
                $count++;
                foreach ($userList as $user) {
                    // $this->info($user->full_name);
                }
            });
    }
}
