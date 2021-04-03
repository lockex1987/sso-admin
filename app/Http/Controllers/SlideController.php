<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Slide;


class SlideController extends Controller
{
    /**
     * Tìm kiếm danh sách.
     */
    public function search(Request $request)
    {
        $size = $request->size;
        $status = $request->status;

        $query = Slide::getQuery();

        if (!empty($status) || $status == '0') {
            $query->where('is_active', $status);
        }

        $query->orderBy('id', 'desc');

        // Nếu không truyền vào size thì lấy tất cả
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

        // Validate
        $rules = [
            'code' => 'required|unique:slide,code' . (empty($id) ? '' : ',' . $id),
            'name' => 'required',
            'type' => 'required'
        ];
        $request->validate($rules);

        if (empty($id)) {
            $slide = new Slide();
        } else {
            $slide = Slide::find($id);
        }
        $slide->code = $request->code;
        $slide->name = str_ireplace(['Tỉnh ', 'Thành phố '], ['', ''], $request->name);
        $slide->type = $request->type;
        $slide->save();
   
        return [
            'code' => 0,
            'message' => 'Stored'
        ];
    }

    /**
     * Xóa.
     */
    public function destroy(Slide $slide)
    {
        $slide->delete();
        return [
            'code' => 0,
            'message' => 'Deleted'
        ];
    }

    /**
     * Khóa / mở khóa slide.
     */
    public function updateIsActive(Request $request)
    {
        $id = $request->id;
        $isActive = $request->isActive;

        $slide = Slide::find($id);
        $slide->is_active = $isActive;
        $slide->save();

        return [
            'code' => 0,
            'message' => 'Updated'
        ];
    }
}
