<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


class LogFileController extends Controller
{
    /**
     * Liệt kê các file trong thư mục log.
     */
    public function list()
    {
        // TODO: Lấy cả kích thước file
        $path = storage_path('logs');
        $files = array_values(array_diff(scandir($path), ['.', '..', '.gitignore']));
        $files = array_map(fn($f) => [
            'name' => $f,
            'size' => filesize($path . '/' . $f),
        ], $files);
        return $files;
    }

    /**
     * Download file.
     */
    public function download(Request $request)
    {
        $path = storage_path('logs/' . $request->fileName);
        $realBase = storage_path();

        // Kiểm tra bug ATTT path traversal
        if (strpos($path, $realBase) == 0) {
            // Nếu bị lỗi "Allowed memory size of xxx bytes exhausted"
            // thì sửa memory_limit=2048M ở php.ini
            return response()->download($path);
        }
    }
}
