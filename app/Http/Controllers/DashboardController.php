<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\App;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Log;


class DashboardController extends Controller
{
    /**
     * Tìm kiếm danh sách.
     */
    public function getStats(Request $request)
    {
        $noOfApp = App::count();
        $noOfPermission = Permission::count();
        $noOfRole = Role::count();
        $noOfUser = User::count();

        // Test log
        // Log::info('Test log info');
        // Log::error('Test log error');
        // Log::channel('errorfile')->error('Chỉ log ra file error');
        // Log::stack(['errorfile', 'errordb'])->error('Log ra file error và DB');

        return [
            'app' => $noOfApp,
            'permission' => $noOfPermission,
            'role' => $noOfRole,
            'user' => $noOfUser
        ];
    }
}
