<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class LogFileController extends Controller
{
    /**
     * Liệt kê các file trong thư mục log.
     */
    public function list(Request $request)
    {
        $path = storage_path('logs');
        $files = array_values(array_diff(scandir($path), ['.', '..', '.gitignore']));
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
            return response()->download($path);
        }
    }
}
