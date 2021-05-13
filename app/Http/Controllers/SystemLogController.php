<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Cache\Auth;
use App\Models\SystemLog;
use Illuminate\Database\Eloquent\Builder;


class SystemLogController extends Controller
{
    /**
     * Lấy danh sách ứng dụng của người dùng.
     */
    public function search(Request $request)
    {
        $query = SystemLog::with('user');
        $this->filter($query, $request);

        return $query
            ->orderBy('created_at', 'desc')
            ->paginate($request->size);
    }

    /**
     * Xóa.
     */
    public function destroy(Request $request)
    {
        $query = SystemLog::getQuery();
        $this->filter($query, $request);

        $query->delete();
        return [
            'code' => 0,
            'message' => 'Deleted',
        ];
    }

    private function filter($query, $request)
    {
        $username = $request->username;
        $type = $request->type;
        $ip = $request->ip;
        $createdFrom = $request->createdFrom;
        $createdTo = $request->createdTo;

        if (! empty($createdFrom)) {
            $query->where('created_at', '>=', $createdFrom);
        }
        if (! empty($createdTo)) {
            $query->where('created_at', '<=', $createdTo);
        }
        if (! empty($ip)) {
            $query->where('ip', 'like', '%' . $ip . '%');
        }
        if (! empty($type)) {
            $query->where('type', $type);
        }
        if (! empty($username)) {
            $query->whereHas('user', function (Builder $query) use ($username) {
                $query->where('username', 'like', '%' . $username . '%');
            });
        }
    }
}
