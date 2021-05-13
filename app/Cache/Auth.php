<?php

namespace App\Cache;

use Illuminate\Support\Facades\Redis;
use Str;

class Auth
{
    /**
     * Lấy thông tin người dùng hiện tại (đang được lưu ở Redis).
     */
    public static function user()
    {
        // Nếu đã có ở $request rồi thì lấy luôn
        $request = request();
        if (! empty($request->attributes->checkUser)) {
            return $request->attributes->user;
        }

        $redisKey = self::getRedisKeyFromRequest();

        $redisValue = Redis::get($redisKey);
        if (! $redisValue) {
            return null;
        }

        $user = json_decode($redisValue);

        // Lưu lại vào $request luôn, để đỡ gọi Redis nhiều lần
        $request->attributes->checkUser = true;
        $request->attributes->user = $user;

        return $user;
    }

    /**
     * Lưu thông tin người dùng vào Redis.
     * Sử dụng sau khi đăng nhập thành công.
     */
    public static function saveUser($user)
    {
        $token = self::generateRandomToken();

        // Sinh code và lưu ở Redis trong 10 ngày
        $expiredTime = 10 * 24 * 60 * 60;
        $redisKey = self::getRedisKeyFromToken($token);
        $redisValue = json_encode([
            'id' => $user->id,
            'username' => $user->username,
        ]);
        Redis::set($redisKey, $redisValue, 'EX', $expiredTime);
        return $token;
    }

    /**
     * Cập nhật thông tin người dùng ở Redis.
     * Sử dụng ở chức năng "View as".
     */
    public static function updateUser($user)
    {
        $redisKey = self::getRedisKeyFromRequest();

        $redisValue = json_encode([
            'id' => $user->id,
            'username' => $user->username,
        ]);

        // Sinh code và lưu ở Redis trong 10 ngày
        $expiredTime = 10 * 24 * 60 * 60;

        Redis::set($redisKey, $redisValue, 'EX', $expiredTime);
    }

    /**
     * Loại bỏ thông tin người dùng khỏi Redis.
     * Sử dụng khi đăng xuất.
     */
    public static function removeUser()
    {
        $redisKey = self::getRedisKeyFromRequest();
        Redis::del($redisKey);
    }

    /**
     * Lấy giá trị token từ request (trong header).
     */
    private static function getToken()
    {
        $request = request();
        $token = str_replace('Bearer ', '', $request->header('Authorization'));
        return $token;
    }

    /**
     * Sinh ra Redis key để lưu token.
     */
    private static function getRedisKeyFromToken($token)
    {
        return 'sso_admin_token:' . $token;
    }

    /**
     * Lấy Redis key từ request hiện tại.
     */
    private static function getRedisKeyFromRequest()
    {
        $request = request();
        $token = self::getToken($request);
        $redisKey = self::getRedisKeyFromToken($token);
        return $redisKey;
    }

    private static function generateRandomToken()
    {
        $token = Str::uuid() . Str::random(100);
        return $token;
    }
}
