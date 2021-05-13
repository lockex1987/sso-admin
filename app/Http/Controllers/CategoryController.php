<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use DB;


class CategoryController extends Controller
{
    /**
     * Tìm kiếm danh sách.
     */
    public function search(Request $request)
    {
        $table = $request->table;
        $search = '%' . $request->search . '%';
        $size = $request->size;
        $pagi = DB::table($table)
            ->where('code', 'like', $search)
            ->orWhere('name', 'like', $search)
            ->orderBy('id', 'desc')
            ->paginate($size);
        return $pagi;
    }

    /**
     * Thêm mới hoặc cập nhật.
     */
    public function store(Request $request)
    {
        $table = $request->table;
        $id = $request->id;

        // Validate
        if (empty($id)) {
            $rules = [
                'code' => 'required|unique:' . $table . ',code',
                'name' => 'required',
            ];
        } else {
            $rules = [
                'code' => 'required|unique:' . $table . ',code,' . $id,
                'name' => 'required',
            ];
        }
        $request->validate($rules);

        if (empty($id)) {
            $category = new Category();
            DB::table($table)
                ->insert([
                    'code' => $request->code,
                    'name' => $request->name,
                ]);
        } else {
            DB::table($table)
                ->where('id', $id)
                ->update([
                    'code' => $request->code,
                    'name' => $request->name,
                ]);
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
        $table = $request->table;
        $id = $request->id;
        DB::table($table)
            ->where('id', $id)
            ->delete();
        return [
            'code' => 0,
            'message' => 'Deleted',
        ];
    }

    /**
     * Lấy một bản ghi.
     */
    public function getOne(Category $category)
    {
        return $category;
    }
}
