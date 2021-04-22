<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use App\Cache\Auth;
use App\Models\User;
use App\Helpers\AvatarGenerator;

class UserController extends Controller
{
    /**
     * Tìm kiếm danh sách.
     */
    public function search(Request $request)
    {
        $search = '%' . $request->search . '%';
        $size = $request->size;
        $query = User::with('organization')
            ->where('username', 'like', $search)
            ->orWhere('email', 'like', $search)
            ->orWhere('full_name', 'like', $search)
            ->orderBy('id', 'desc');

        // Nếu không truyền vào size thì lấy tất cả (khi export)
        if (empty($size)) {
            return $query->get();
        }

        // Trả về phân trang
        return $query->paginate($size);
    }
     
    /**
     * Thêm mới hoặc cập nhật.
     */
    public function store(Request $request)
    {
        $id = $request->id;
        $avatar = $request->avatar;
        $username = $request->username;
        $fullName = $request->fullName;
        $email = $request->email;
        $organizationId = $request->organizationId;
        $password = $request->password;

        // Validate
		$rules = [
			'username' => [
                'required',
                'unique:user,username' . (empty($id) ? '' : ',' . $id)
            ],
			'fullName' => 'required',
			'email' => 'email',
            'avatar' => [
                'mimes:png,jpg,jpeg,gif',
                'max:2048'
            ]
		];
		if (empty($id)) {
			$rules['password'] = 'required';
		}
        $request->validate($rules);

        if (empty($id)) {
            $user = new User();

            $user->password = Hash::make($password);
            $user->is_active = 1;
        } else {
            $user = User::find($id);

            if (!empty($password)) {
                $user->password = Hash::make($password);
            }
        }

        $user->username = $username;
        $user->full_name = $fullName;
        $user->email = $email;
        $user->organization_id = $organizationId;
        $user->save();

        if (!empty($avatar)) {
            $passportUrl = config('services.sso.passportUrl');

            // Xóa ảnh cũ
            if ($user->avatar) {
                if (str_starts_with($user->avatar, $passportUrl)) {
                    $relativePath = str_replace($passportUrl . '/storage/avatars/', 'avatars/', $user->avatar);
                    Storage::disk('ssoPassport')->delete($relativePath);
                }
            }

            $avatarName = $user->id . '_avatar_' . time() . '.' . $avatar->getClientOriginalExtension();
            Storage::disk('ssoPassport')->putFileAs('avatars', $avatar, $avatarName);

            $user->avatar = $passportUrl . '/storage/avatars/' . $avatarName;
            $user->save();
        }

        // Tự sinh avatar
        if (empty($user->avatar)) {
            AvatarGenerator::autoGenerateAvatar($user);
        }
   
        return [
            'code' => 0,
            'message' => 'Stored'
        ];
    }

    /**
     * Xóa.
     */
    public function destroy(Request $request)
    {
        // $user->apps()->detach();
        $id = $request->id;
        // dd($id);
        User::find($id)->delete();
        return [
            'code' => 0,
            'message' => 'Deleted'
        ];
    }

    /**
     * Khóa / mở khóa người dùng.
     */
    public function updateIsActive(Request $request)
    {
        $id = $request->id;
        $isActive = $request->isActive;

        $user = User::find($id);
        $user->is_active = $isActive;
        $user->save();

        return [
            'code' => 0,
            'message' => 'Updated'
        ];
    }

    /**
     * Xem theo tài khoản người khác.
     */
    public function viewAs(Request $request)
    {
        $this->checkPermission('user.view-as');

        $id = $request->id;
        $user = User::find($id);
        Auth::updateUser($user);
        return [
            'code' => 0,
            'message' => 'Success'
        ];
    }

    /**
     * Lấy danh sách vai trò.
     */
    public function getRoles(Request $request)
    {
        $id = $request->id;
        $user = User::find($id);
        return $user->roles;
    }

    /**
     * Cập nhật danh sách vai trò.
     */
    public function updateRoles(Request $request)
    {
        $id = $request->id;
        $roles = $request->roles;

        $user = User::find($id);

        $user->roles()->detach();
        $user->roles()->attach($roles);

        return [
            'code' => 0
        ];
    }

    /**
     * Lấy danh sách ứng dụng.
     */
    public function getApps(Request $request)
    {
        $id = $request->id;
        $user = User::find($id);
        return $user->apps;
    }

    /**
     * Cập nhật danh sách ứng dụng.
     */
    public function updateApps(Request $request)
    {
        $id = $request->id;
        $apps = $request->apps;

        $user = User::find($id);

        $user->apps()->detach();
        $user->apps()->attach($apps);

        return [
            'code' => 0
        ];
    }
}
