<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SystemConfig;


class ConfigController extends Controller
{
    /**
     * Tìm kiếm danh sách.
     */
    public function search(Request $request)
    {
        $search = '%' . $request->search . '%';
        $size = $request->size;
        $pagi = SystemConfig::where('code', 'like', $search)
            ->orWhere('name', 'like', $search)
            ->orWhere('value', 'like', $search)
            ->orderBy('id', 'desc')
            ->paginate($size);
        return $pagi;
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
                'code' => 'required|unique:system_config,code',
                'name' => 'required',
                'value' => 'required'
            ];
        } else {
            $rules = [
                'code' => 'required|unique:system_config,code,' . $id,
                'name' => 'required',
                'value' => 'required'
            ];
        }
        $request->validate($rules);

        if (empty($id)) {
            $config = new SystemConfig();
        } else {
            $config = SystemConfig::find($id);
        }

        if (! $config->strict) {
            $config->code = $request->code;
            $config->name = $request->name;
        }
        $config->value = $request->value;
        $config->save();
   
        return [
            'code' => 0,
            'message' => 'Stored'
        ];
    }

    /**
     * Xóa.
     */
    public function destroy(SystemConfig $config)
    {
        if ($config->strict) {
            return [
                'code' => 2,
                'message' => 'Bạn không được xóa cấu hình này'
            ];
        }

        $config->delete();
        return [
            'code' => 0,
            'message' => 'Deleted'
        ];
    }
}
