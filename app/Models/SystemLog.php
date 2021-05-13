<?php

namespace App\Models;

class SystemLog extends BaseModel
{
    public static function insertSystemLog($request, $type, $userId = null, $description = null)
    {
        $systemLog = new SystemLog();
        $systemLog->ip = $request->ip();
        // TODO: Parse user agent thành browser (Firefox, Chrome, Edge, Chromium,...) và os (Windows, Ubuntu, Linux,...)
        $systemLog->user_agent = $request->header('User-Agent');;
        $systemLog->created_at = now();
        $systemLog->type = $type;
        $systemLog->user_id = $userId;
        $systemLog->description = $description;
        $systemLog->save();
    }

    /**
     * Người dùng tác động.
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }
}
