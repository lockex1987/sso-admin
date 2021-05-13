<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\District;
use App\Models\Commune;


class DistrictController extends Controller
{
    /**
     * Tìm kiếm danh sách.
     */
    public function search(Request $request)
    {
        $search = '%' . $request->search . '%';
        $size = $request->size;
        $type = $request->type;
        $provinceId = $request->provinceId;

        $query = District::with('province')
            ->where(function ($query) use ($search) {
                $query->where('name', 'like', $search)
                    ->orWhere('code', 'like', $search);
            });

        if (! empty($type)) {
            $query->where('type', $type);
        }

        if (! empty($provinceId)) {
            $query->where('province_id', $provinceId);
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
        $provinceId = $request->provinceId;

        $query = District::where(function ($query) use ($search) {
            $query->where('name', 'like', $search)
                ->orWhere('code', 'like', $search);
        });

        if (! empty($type)) {
            $query->where('type', $type);
        }

        if (! empty($provinceId)) {
            $query->where('province_id', $provinceId);
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
            'code' => 'required|unique:district,code' . (empty($id) ? '' : ',' . $id),
            'name' => 'required',
            'type' => 'required',
            'provinceId' => 'required',
        ];
        $request->validate($rules);

        if (empty($id)) {
            $district = new District();
        } else {
            $district = District::find($id);

            if ($district->province_id != $request->provinceId) {
                Commune::where('district_id', $district->id)
                    ->update([
                        'province_id' => $request->provinceId,
                    ]);
            }
        }
        $district->code = $request->code;
        $district->name = str_ireplace(['Huyện ', 'Quận ', 'Thị xã ', 'Thành phố '], ['', '', '', ''], $request->name);
        $district->type = $request->type;
        $district->province_id = $request->provinceId;

        $district->type = $request->type;
        $district->save();

        return [
            'code' => 0,
            'message' => 'Stored',
        ];
    }

    /**
     * Xóa.
     */
    public function destroy(District $district)
    {
        $district->delete();
        return [
            'code' => 0,
            'message' => 'Deleted',
        ];
    }
}
