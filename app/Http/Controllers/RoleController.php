<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;


class RoleController extends Controller
{
    /**
     * Tìm kiếm danh sách.
     */
    public function search(Request $request)
    {
        $search = '%' . $request->search . '%';
        $size = $request->size;
        $pagi = Role::where('code', 'like', $search)
            ->orWhere('name', 'like', $search)
            ->orderBy('id', 'desc')
            ->paginate($size);
        return $pagi;
    }

    /**
     * Lấy toàn bộ danh sách.
     */
    public function getAll(Request $request)
    {
        $list = Role::orderBy('code')
            ->get();
        return $list;
    }

    /**
     * Thêm mới hoặc cập nhật.
     */
    public function store(Request $request)
    {
        $id = $request->id;

        // Validate
        if (empty($id)) {
            $rules = [
                'code' => 'required|unique:role,code',
                'name' => 'required',
            ];
        } else {
            $rules = [
                'code' => 'required|unique:role,code,' . $id,
                'name' => 'required',
            ];
        }
        $request->validate($rules);

        if (empty($id)) {
            $role = new Role();
        } else {
            $role = Role::find($id);
        }

        $role->code = $request->code;
        $role->name = $request->name;
        $role->save();

        return [
            'code' => 0,
            'message' => 'Stored',
        ];
    }

    /**
     * Xóa.
     */
    public function destroy(Request $request)
    {
        $id = $request->id;
        Role::destroy($id);
        return [
            'code' => 0,
            'message' => 'Deleted',
        ];
    }

    /**
     * Lấy danh sách quyền.
     */
    public function getPermissions(Request $request)
    {
        $id = $request->id;
        $role = Role::find($id);
        return $role->permissions;
    }

    /**
     * Cập nhật danh sách quyền.
     */
    public function updatePermissions(Request $request)
    {
        $id = $request->id;
        $permissions = $request->permissions;

        $role = Role::find($id);

        $role->permissions()->detach();
        $role->permissions()->attach($permissions);

        return [
            'code' => 0,
        ];
    }
}
