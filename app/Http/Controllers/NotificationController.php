<?php

namespace App\Http\Controllers;

use App\Cache\Auth;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;


class NotificationController extends Controller
{
    /**
     * Tìm kiếm danh sách.
     */
    public function search(Request $request)
    {
        $user = Auth::user();

        // Tạo thông báo
        /*
        for ($i = 0; $i < 100; $i++) {
            $this->createNotification('Generate video <b>Chu Chu</b> thất bại ' . $i);
        }
        */

        $search = '%' . $request->search . '%';
        $size = $request->size;
        $pagi = Notification::where('message', 'like', $search)
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate($size);
        return $pagi;
    }

    /**
     * Lấy số lượng thông báo chưa đọc.
     */
    public function getUnreadNumber()
    {
        $user = Auth::user();
        $number = Notification::whereNull('read_at')
            ->where('user_id', $user->id)
            ->count();

        return [
            'code' => 0,
            'number' => $number,
        ];
    }

    /**
     * Đánh dấu đã đọc.
     */
    public function markRead(Request $request)
    {
        $id = $request->id;
        $notification = Notification::find($id);
        $user = Auth::user();

        // Kiểm tra, chỉ có thể đánh dấu với thông báo của người dùng hiện tại
        if ($user->id != $notification->user_id) {
            return;
        }

        $notification->read_at = now();
        $notification->save();

        return [
            'code' => 0,
            'message' => 'Update',
        ];
    }

    /**
     * Demo publish Redis.
     */
    public function testPublish(Request $request)
    {
        $faker = \Faker\Factory::create();
        $message = $faker->text(200);
        $this->createNotification($message);

        $user = Auth::user();
        $channel = 'sso-admin-notification';
        Redis::publish($channel, json_encode([
            'message' => $message,
            'userId' => $user->id,
            'username' => $user->username,
        ]));

        return [
            'code' => 0,
            'message' => 'Published',
        ];
    }

    private function createNotification($message)
    {
        $user = Auth::user();

        $notification = new Notification();
        $notification->user_id = $user->id;
        $notification->created_at = now();
        $notification->message = $message;
        $notification->save();
    }
}
