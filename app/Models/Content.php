<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/*
CREATE TABLE `content` (
	`id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID tự tăng',
	`title` VARCHAR(500) NOT NULL COMMENT 'Tiêu đề' COLLATE 'utf8mb4_unicode_ci',
	`description` VARCHAR(1000) NOT NULL COMMENT 'Mô tả ngắn' COLLATE 'utf8mb4_unicode_ci',
	`thumbnail` VARCHAR(2000) NULL DEFAULT NULL COMMENT 'Đường dẫn ảnh đại diện' COLLATE 'utf8mb4_unicode_ci',
	`content` LONGTEXT NOT NULL COMMENT 'Nội dung' COLLATE 'utf8mb4_unicode_ci',
	`status` TINYINT(3) UNSIGNED NULL DEFAULT NULL COMMENT 'Trạng thái (1 - draft, 2 - publish)',
	`type` TINYINT(3) UNSIGNED NULL DEFAULT NULL COMMENT 'Loại (1 - tin tức, 2 - page: điều khoản sử dụng, về chúng tôi,...)',
	`published_date` DATE NULL DEFAULT NULL COMMENT 'Ngày xuất bản tin tức',
	`created_at` TIMESTAMP NULL DEFAULT NULL COMMENT 'Thời điểm tạo',
	`updated_at` TIMESTAMP NULL DEFAULT NULL COMMENT 'Thời điểm update',
	PRIMARY KEY (`id`)
)
COLLATE='utf8mb4_unicode_ci'
ENGINE=InnoDB
AUTO_INCREMENT=4
;
*/
class Content extends Model
{
    protected $table = 'content';

    // Mặc định là không trả về content cho nhanh
    // vì content độ dài lớn
    protected $hidden = ['content'];
}
