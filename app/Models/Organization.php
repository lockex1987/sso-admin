<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    public $timestamps = false;

    protected $table = 'organization';

    /**
     * Tổ chức cha.
     */
    public function parent()
    {
        return $this->belongsTo('App\Models\Organization', 'parent_id');
    }
}
