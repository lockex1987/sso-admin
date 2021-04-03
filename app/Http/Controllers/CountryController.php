<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Country;


class CountryController extends Controller
{
    /**
     * Tìm kiếm danh sách.
     */
    public function search(Request $request)
    {
        $search = '%' . $request->search . '%';
        $size = $request->size;
        $query = Country::where('code', 'like', $search)
            ->orWhere('name', 'like', $search)
            ->orderBy('id', 'desc');

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
        $num = Country::where('code', 'like', $search)
            ->orWhere('name', 'like', $search)
            ->delete();
        return [
            'code' => 0,
            'message' => 'Deleted',
            'num' => $num
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
            'code' => 'required|unique:country,code' . (empty($id) ? '' : ',' . $id),
            'name' => 'required'
        ];
        $request->validate($rules);

        if (empty($id)) {
            $country = new Country();
        } else {
            $country = Country::find($id);
        }
        $country->code = $request->code;
        $country->name = $request->name;
        $country->save();
   
        return [
            'code' => 0,
            'message' => 'Stored'
        ];
    }

    /**
     * Xóa.
     */
    public function destroy(Country $country)
    {
        $country->delete();
        return [
            'code' => 0,
            'message' => 'Deleted'
        ];
    }
}
