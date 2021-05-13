<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Province;


class ProvinceController extends Controller
{
    /**
     * Tìm kiếm danh sách.
     */
    public function search(Request $request)
    {
        $search = '%' . $request->search . '%';
        $size = $request->size;
        $type = $request->type;

        $query = Province::where(function ($query) use ($search) {
            $query->where('name', 'like', $search)
                ->orWhere('code', 'like', $search);
        });

        if (! empty($type)) {
            $query->where('type', $type);
        }

        $query->orderBy('id', 'desc');

        // Nếu không truyền vào size thì lấy tất cả (khi export)
        if (empty($size)) {
            return $query->get();
        }

        // Trả về phân trang
        return $query->paginate($size);
    }

    /**
     * Xóa các bản ghi tìm thấy.
     */
    public function destroyMultiple(Request $request)
    {
        $search = '%' . $request->search . '%';
        $type = $request->type;

        $query = Province::where(function ($query) use ($search) {
            $query->where('name', 'like', $search)
                ->orWhere('code', 'like', $search);
        });

        if (! empty($type)) {
            $query->where('type', $type);
        }

        $num = $query->delete();
        return [
            'code' => 0,
            'message' => 'Deleted',
            'num' => $num,
        ];
    }

    /**
     * Thêm mới hoặc cập nhật.
     */
    public function store(Request $request)
    {
        $id = $request->id;

        // Validate
        $rules = [
            'code' => 'required|unique:province,code' . (empty($id) ? '' : ',' . $id),
            'name' => 'required',
            'type' => 'required',
        ];
        $request->validate($rules);

        if (empty($id)) {
            $province = new Province();
        } else {
            $province = Province::find($id);
        }
        $province->code = $request->code;
        $province->name = str_ireplace(['Tỉnh ', 'Thành phố '], ['', ''], $request->name);
        $province->type = $request->type;
        $province->save();

        return [
            'code' => 0,
            'message' => 'Stored',
        ];
    }

    /**
     * Xóa.
     */
    public function destroy(Province $province)
    {
        $province->delete();
        return [
            'code' => 0,
            'message' => 'Deleted',
        ];
    }
}
