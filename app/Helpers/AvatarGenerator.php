<?php

namespace App\Helpers;

/**
 * Tham khảo:
 *   https://dev.to/thinkverse/creating-default-user-initial-avatars-in-php-7-1gf1
 */
class AvatarGenerator
{
    /**
     * Tạo ảnh avatar mặc định.
     */
    public static function createDefaultAvatar(
        string $text,
        string $filePath
    ): void {
        $text = self::getInitialCharacters($text);

        $font = public_path('fonts/OpenSans-Regular.ttf');
        $bgColor = self::getRandomBgColor();
        $textColor = [255, 255, 255];
        $fontSize = 140;
        $width = 400;
        $height = 400;

        $image = imagecreate($width, $height);
        imagecolorallocate($image, $bgColor[0], $bgColor[1], $bgColor[2]);
        $fontColor = imagecolorallocate($image, $textColor[0], $textColor[1], $textColor[2]);
        $textBoundingBox = imagettfbbox($fontSize, 0, $font, $text);
        $y = abs(ceil(($height - $textBoundingBox[5]) / 2));
        $x = abs(ceil(($width - $textBoundingBox[2]) / 2));
        imagettftext($image, $fontSize, 0, $x, $y, $fontColor, $font, $text);
        imagepng($image, $filePath);
        imagedestroy($image);
    }

    private static function getRandomBgColor(): array
    {
        $colors = [
            '#1abc9c', '#2ecc71', '#3498db', '#9b59b6', '#34495e', '#16a085', '#27ae60',
            '#2980b9', '#8e44ad', '#2c3e50', '#f1c40f', '#e67e22',
            '#e74c3c', '#95a5a6', '#f39c12', '#d35400', '#c0392b', '#bdc3c7', '#7f8c8d'
        ];
        $idx = rand(0, count($colors) - 1);
        $hex = $colors[$idx];
        // Chuyển từ hex sang RGB
        [$r, $g, $b] = sscanf($hex, "#%02x%02x%02x");
        return [$r, $g, $b];
    }

    private static function getInitialCharacters(string $fullName): string
    {
        $fullName = strtoupper($fullName);
        $a = explode(' ', $fullName);
        if (count($a) == 1) {
            return self::getFirstCharacter($a[0]);
        }
        return self::getFirstCharacter($a[0]) . self::getFirstCharacter($a[1]);
    }

    private static function getFirstCharacter(string $text): string
    {
        return mb_substr($text, 0, 1);
    }
}
