<?php

namespace App\Http\Controllers;

use App\Models\Commune;
use App\Models\District;
use Illuminate\Http\Request;


class CommuneController extends Controller
{
    /**
     * Tìm kiếm danh sách.
     */
    public function search(Request $request)
    {
        $query = Commune::with(['district', 'province']);
        $this->filterSearch($request, $query);
        $query->orderBy('id', 'desc');

        // Nếu không truyền vào size thì lấy tất cả (khi export)
        $size = $request->size;
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
        $query = Commune::getQuery();
        $this->filterSearch($request, $query);

        $num = $query->delete();
        return [
            'code' => 0,
            'message' => 'Deleted',
            'num' => $num,
        ];
    }

    /**
     * Thêm mới hoặc cập nhật.
     * Commune insert dữ liệu theo batch cho nhanh
     * foreach (collect($wardsData)->chunk(1000) as $wards) { $this->insert($tableNames['wards'], $wards); }
     */
    public function store(Request $request)
    {
        $id = $request->id;

        // Validate
        $rules = [
            'code' => 'required|unique:commune,code' . (empty($id) ? '' : ',' . $id),
            'name' => 'required',
            'type' => 'required',
            'districtId' => 'required_without:districtCode',
            'districtCode' => 'required_without:districtId',
            'provinceId' => 'required_with:districtId',
        ];
        $request->validate($rules);

        if (empty($id)) {
            $commune = new Commune();
        } else {
            $commune = Commune::find($id);
        }

        $districtId = $request->districtId;
        $provinceId = $request->provinceId;

        if (empty($districtId)) {
            $district = District::where('code', $request->districtCode)->first();
            if (! $district) {
                return [
                    'code' => 1,
                    'message' => 'Mã huyện / quận không tồn tại',
                ];
            }

            $districtId = $district->id;
            $provinceId = $district->province_id;
        }

        $commune->code = $request->code;
        $commune->name = str_ireplace(['Xã ', 'Phường ', 'Thị trấn '], ['', '', '', ''], $request->name);
        $commune->type = $request->type;
        $commune->district_id = $districtId;
        $commune->province_id = $provinceId;
        $commune->type = $request->type;
        $commune->save();

        return [
            'code' => 0,
            'message' => 'Stored',
        ];
    }

    /**
     * Xóa.
     */
    public function destroy(Commune $commune)
    {
        $commune->delete();
        return [
            'code' => 0,
            'message' => 'Deleted',
        ];
    }

    private function filterSearch(Request $request, $query)
    {
        $type = $request->type;
        $provinceId = $request->provinceId;
        $districtId = $request->districtId;

        if (! empty($request->search)) {
            $search = '%' . $request->search . '%';

            $query->where(function ($query) use ($search) {
                $query->where('name', 'like', $search)
                    ->orWhere('code', 'like', $search);
            });
        }

        if (! empty($type)) {
            $query->where('type', $type);
        }

        if (! empty($provinceId)) {
            $query->where('province_id', $provinceId);
        }

        if (! empty($districtId)) {
            $query->where('district_id', $districtId);
        }
    }
}
