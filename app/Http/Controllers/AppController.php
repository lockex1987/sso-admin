<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\App;


/**
 * Quản lý ứng dụng.
 */
class AppController extends Controller
{
    /**
     * Tìm kiếm danh sách.
     */
    public function search(Request $request)
    {
        $search = '%' . $request->search . '%';
        $size = $request->size;
        $pagi = App::where('code', 'like', $search)
            ->orWhere('name', 'like', $search)
            ->orWhere('login_redirect', 'like', $search)
            ->orWhere('logout_redirect', 'like', $search)
            ->orderBy('id', 'desc')
            ->paginate($size);
        return $pagi;
    }

    /**
     * Lấy toàn bộ danh sách.
     */
    public function getAll(Request $request)
    {
        $list = App::orderBy('code')
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
                'code' => 'required|unique:app,code',
                'name' => 'required',
                'loginRedirect' => 'required',
                // 'logoutRedirect' => 'required',
            ];
        } else {
            $rules = [
                'code' => 'required|unique:app,code,' . $id,
                'name' => 'required',
                'loginRedirect' => 'required',
                // 'logoutRedirect' => 'required',
            ];
        }
        $request->validate($rules);

        if (empty($id)) {
            $app = new App();
        } else {
            $app = App::find($id);
        }

        $app->code = $request->code;
        $app->name = $request->name;
        $app->login_redirect = $request->loginRedirect;
        $app->logout_redirect = $request->logoutRedirect;
        $app->save();

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
        // $app->users()->detach();
        $id = $request->id;
        App::find($id)->delete();
        return [
            'code' => 0,
            'message' => 'Deleted',
        ];
    }
}
