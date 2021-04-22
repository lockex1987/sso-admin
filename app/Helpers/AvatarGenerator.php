<?php

namespace App\Helpers;

/**
 * Tham khảo:
 *   https://dev.to/thinkverse/creating-default-user-initial-avatars-in-php-7-1gf1
 *   https://github.com/LasseRafn/php-initial-avatar-generator
 */
class AvatarGenerator
{
    /**
     * Tạo ảnh avatar mặc định.
     */
    public static function createDefaultAvatar(
        string $text,
        string $filePath,
        array $bgColor = [0, 0, 0],
        array $textColor = [255, 255, 255],
        int $fontSize = 140,
        int $width = 600,
        int $height = 600
    ): void {
        $font = public_path('fonts/OpenSans-Regular.ttf');

        $image = @imagecreate($width, $height)
            or die("Cannot Initialize new GD image stream");
        imagecolorallocate($image, $bgColor[0], $bgColor[1], $bgColor[2]);
        $fontColor = imagecolorallocate($image, $textColor[0], $textColor[1], $textColor[2]);
        $textBoundingBox = imagettfbbox($fontSize, 0, $font, $text);
        $y = abs(ceil(($height - $textBoundingBox[5]) / 2));
        $x = abs(ceil(($width - $textBoundingBox[2]) / 2));
        imagettftext($image, $fontSize, 0, $x, $y, $fontColor, $font, $text);
        imagepng($image, $filePath);
        imagedestroy($image);
    }
}
