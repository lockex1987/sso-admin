<?php

namespace App\Helpers;

use GuzzleHttp\Client;

class Downloader
{
    // TODO: Get link trực tiếp
    private const DOWNLOAD_URL = 'https://github.com/kjmtrue/vietnam-zone/raw/database/vietnam-zone.xls';

    /**
     * Download database VietNam Zone
     *
     * @return string|null
     */
    public function downloadFile()
    {
        $client = new Client([
            'verify' => false
        ]);
        $res = $client->get(self::DOWNLOAD_URL, [
            'save_to' => storage_path('vietnam-zone.xls')
        ]);
        return $res->getStatusCode() == 200 ? storage_path('vietnam-zone.xls') : null;
    }
}