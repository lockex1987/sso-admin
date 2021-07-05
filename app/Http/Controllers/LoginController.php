<?php

namespace App\Http\Controllers;

use App\Cache\Auth;
use App\Helpers\Sso;
use App\Models\User;
use Illuminate\Support\Facades\DB;


class LoginController extends Controller
{
    /**
     * Thực hiện đăng xuất.
     */
    public function logout()
    {
        Auth::removeUser();
        return [
            'code' => 0
        ];
    }

    /**
     * Xử lý khi đã đăng nhập thành công ở sso-passport, sso-passport trả lại code.
     */
    public function loginCallback()
    {
        $obj = Sso::checkLoginCallback();

        if (! empty($obj->error)) {
            return [
                'code' => 1,
                'messsage' => 'Lỗi khi lấy người dùng từ SSO'
            ];
        }

        $user = $this->findOrCreateUser($obj);
        $token = Auth::saveUser($user);
        $permissions = $this->getUserPermissions($user->id);
        return [
            'code' => 0,
            'token' => $token,
            'user' => $user,
            'permissions' => $permissions,
        ];
    }

    /**
     * Lấy thông tin người dùng từ token truyền vào.
     */
    public function getUserInfo()
    {
        $redisUser = Auth::user();

        if ($redisUser == null) {
            return [
                'code' => 2
            ];
        }

        $id = $redisUser->id;
        $user = User::find($id);

        if (! $user) {
            return [
                'code' => 1
            ];
        }

        $permissions = $this->getUserPermissions($id);
        return [
            'code' => 0,
            'user' => $user,
            'permissions' => $permissions
        ];
    }

    /**
     * Kiểm tra xem đã tồn tại người dùng hay chưa.
     * Nếu chưa tồn tại thì thêm mới.
     */
    private function findOrCreateUser($passportUser)
    {
        $authUser = User::where('username', $passportUser->username)
            ->first();
        if (! $authUser) {
            $authUser = new User();
            $authUser->username = $passportUser->username;
        }

        // Đồng bộ email, avatar, full_name
        // TODO: Passport cần trả về avatar, full_name
        $authUser->email = $passportUser->email;
        // $authUser->avatar = $passportUser->avatar;
        // $authUser->full_name = $passportUser->full_name;
        $authUser->save();

        return $authUser;
    }

    /**
     * Lấy danh sách mã quyền được phân cho người dùng.
     */
    private function getUserPermissions($userId)
    {
        $sql = <<<'SQL'
            select distinct(p.code) as code
            from user_role ur, role_permission rp, permission p
            where ur.user_id = ?
            and rp.role_id = ur.role_id
            and p.id = rp.permission_id
            SQL;

        $collections = DB::select($sql, [$userId]);
        $permissions = array_map(function ($r) {
            return $r->code;
        }, $collections);
        return $permissions;
    }
}
