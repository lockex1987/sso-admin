<?php

namespace App\Models;

class Role extends BaseModel
{
    //protected $table = 'role';

    /**
     * Danh sách quyền của vai trò.
     */
    public function permissions()
    {
        return $this->belongsToMany('App\Models\Permission', 'role_permission');
    }
}
