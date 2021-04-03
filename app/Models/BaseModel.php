<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Str;

class BaseModel extends Model
{
    // use \Eloquence\Behaviours\CamelCasing;

    public $timestamps = false;

    /**
     * Get the table associated with the model.
     * Ghi đè phương thức này, không chuyển thành số nhiều.
     *
     * @return string
     */
    public function getTable()
    {
        // Bỏ hàm Str::pluralStudly()
        return $this->table ?? Str::snake(class_basename($this));
    }
}
