<?php

namespace App\Models;

class User extends BaseModel
{
    //use \Illuminate\Notifications\Notifiable

    //protected $table = 'user';

    // Ẩn cột password ở JSON trả về
    protected $hidden = [
        'password',
    ];

    /**
     * Danh sách vai trò của người dùng.
     */
    public function roles()
    {
        return $this->belongsToMany('App\Models\Role', 'user_role');
    }

    /**
     * Danh sách ứng dụng của người dùng.
     */
    public function apps()
    {
        return $this->belongsToMany('App\Models\App', 'user_app');
    }

    /**
     * Tổ chức cha.
     */
    public function organization()
    {
        return $this->belongsTo('App\Models\Organization', 'organization_id');
    }
}
