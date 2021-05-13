<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;


class OrganizationController extends Controller
{
    /**
     * Tìm kiếm danh sách.
     */
    public function search(Request $request)
    {
        $search = '%' . $request->search . '%';
        $parentId = $request->parentId;
        $size = $request->size;

        $query = Organization::with('parent')
            ->where(function ($query) use ($search) {
                $query->where('name', 'like', $search)
                    ->orWhere('description', 'like', $search);
            });

        if (! empty($parentId)) {
            $query->where('path', 'like', '%/' . $parentId . '/%');
        }

        $pagi = $query
            ->orderBy('path')
            // ->orderBy('id', 'desc')
            ->paginate($size);
        return $pagi;
    }

    /**
     * Lấy toàn bộ danh sách.
     */
    public function getAll(Request $request)
    {
        $list = Organization::orderBy('name')
            ->get();
        return $list;
    }

    /**
     * Thêm mới hoặc cập nhật.
     */
    public function store(Request $request)
    {
        $id = $request->id;
        $newParentId = $request->parentId;

        if (empty($id)) {
            $rules = [
                'name' => [
                    'required',
                    Rule::unique('organization')
                        ->where(function ($query) use ($newParentId) {
                            // Validate name là duy nhất ở cùng cha
                            // Cùng cha thì không được trùng tên
                            if (empty($newParentId)) {
                                $query->whereNull('parent_id');
                            } else {
                                $query->where('parent_id', $newParentId);
                            }
                        }),
                ],
            ];
        } else {
            $rules = [
                'name' => [
                    'required',
                    Rule::unique('organization')
                        ->ignore($id) // khi sửa thì bỏ qua ID
                        ->where(function ($query) use ($newParentId) {
                            if (empty($newParentId)) {
                                $query->whereNull('parent_id');
                            } else {
                                $query->where('parent_id', $newParentId);
                            }
                        }),
                ],
            ];
        }
        $request->validate($rules);

        // Tổ chức cha
        $newParentObj = $newParentId ? Organization::find($newParentId) : null;

        // Kiểm tra quan hệ vòng tròn
        // Đã validate ở giao diện nhưng vẫn phải validate cả ở đây
        if ($newParentObj && ! empty($id) && $this->isCircleRelationship($id, $newParentObj)) {
            return [
                'code' => 1,
                'message' => 'Quan hệ vòng tròn',
            ];
        }

        if (empty($id)) {
            $org = new Organization();
        } else {
            $org = Organization::find($id);

            // Đổi tổ chức cha
            if ($org->parent_id != $newParentId) {
                $this->updatePathOfDescendants($org, $newParentObj);

                $org->path = ($newParentObj ? $newParentObj->path : '') . $org->id . '/';
            }
        }

        $org->name = $request->name;
        $org->parent_id = $newParentId;
        $org->description = $request->description;
        $org->save();

        if (empty($id)) {
            // Phải chờ có ID ở bước trên
            $org->path = ($newParentObj ? $newParentObj->path : '/') . $org->id . '/';
            $org->save();
        }

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
        // Chú ý cần xóa cả các bản ghi con
        $id = $request->id;
        $org = Organization::find($id);
        Organization::where('path', 'like', $org->path . '%')
            ->delete();

        return [
            'code' => 0,
            'message' => 'Deleted',
        ];
    }

    /**
     * Kiểm tra xem có bị quan hệ vòng tròn hay không.
     */
    private function isCircleRelationship($id, $newParentObj)
    {
        return (strpos($newParentObj->path, '/' . $id . '/') !== false);
    }

    /**
     * Cập nhật path của các đơn vị con của đơn vị hiện tại
     * khi đổi đơn vị cha của đơn vị hiện tại.
     */
    private function updatePathOfDescendants($org, $newParentObj)
    {
        // Cập nhật cả các đơn vị con của $org
        $oldPath = $org->path;
        $newPath = ($newParentObj ? $newParentObj->path : '/') . $org->id . '/';

        $sql = <<<'SQL'
                        update organization
                        set path = replace(path, ?, ?)
                        where path like ?
            SQL;

        DB::update($sql, [$oldPath, $newPath, $oldPath . '_%']);
    }
}
