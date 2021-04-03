<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Permission;


class PermissionController extends Controller
{
    /**
     * Tìm kiếm danh sách.
     */
    public function search(Request $request)
    {
        $search = '%' . $request->search . '%';
        $size = $request->size;
        $pagi = Permission::where('code', 'like', $search)
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
        $list = Permission::orderBy('code')
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
                'code' => 'required|unique:permission,code',
                'name' => 'required'
            ];
        } else {
            $rules = [
                'code' => 'required|unique:permission,code,' . $id,
                'name' => 'required'
            ];
        }
        $request->validate($rules);

        if (empty($id)) {
            $permission = new Permission();
        } else {
            $permission = Permission::find($id);
        }

        $permission->code = $request->code;
        $permission->name = $request->name;
        $permission->save();
   
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
        $id = $request->id;
        Permission::find($id)->delete();
        return [
            'code' => 0,
            'message' => 'Deleted'
        ];
    }
}
