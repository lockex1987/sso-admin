<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class BaseModel extends Model
{
    // use \Eloquence\Behaviours\CamelCasing;

    public $timestamps = false;

    /**
     * Lấy bảng trong CSDL mà liên kết với model này.
     * Ghi đè phương thức này, không chuyển thành số nhiều.
     *
     * @return string Tên bảng
     */
    public function getTable()
    {
        // Bỏ hàm Str::pluralStudly()
        return $this->table ?? Str::snake(class_basename($this));
    }
}
