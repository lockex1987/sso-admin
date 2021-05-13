<?php

namespace App\Http\Controllers;

use App\Cache\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;


class Controller extends BaseController
{
    use AuthorizesRequests,
        DispatchesJobs,
        ValidatesRequests;

    /**
     * Kiểm tra quyền.
     */
    protected function checkPermission($permissionCode)
    {
        $sql = <<<'SQL'
                select count(1) as count
                from permission p, role_permission rp, user_role ur
                where p.code = ?
                and rp.permission_id = p.id
                and ur.role_id = rp.role_id
                and ur.user_id = ?
            SQL;

        $userId = Auth::user()->id;
        $arr = DB::selectOne($sql, [$permissionCode, $userId]);
        $count = $arr->count;
        if ($count == 0) {
            abort(403, 'Forbidden');
            return false;
        }
        return true;
    }

    /**
     * Phân quyền theo miền dữ liệu.
     */
    protected function authoriseVideo($video)
    {
        if ($realValue != $expectedValue) {
        }
        $this->checkPermission($video->user_id, auth()->id());
    }
}
