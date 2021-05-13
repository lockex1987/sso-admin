<?php

namespace App\Logging;

use Illuminate\Support\Facades\DB;
use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Logger;

/**
 * Tham khảo:
 * https://github.com/markhilton/monolog-mysql/blob/master/src/Logger/Monolog/Handler/MysqlHandler.php
 */
class MySqlLoggingHandler extends AbstractProcessingHandler
{
    /*
    public function __construct($level = Logger::DEBUG, $bubble = true)
    {
         parent::__construct($level, $bubble);
    }
    */

    protected function write(array $record): void
    {
        // dd($record);
        // TODO: Khi thực hiện lệnh php artisan đang bị lỗi chỗ SERVER_ADDR
        /*
        $data = [
            'description'     => $record['message'],
            // 'context'         => json_encode($record['context']),
            // 'level'           => $record['level'],
            // 'level_name'      => $record['level_name'],
            // 'channel'         => $record['channel'],
            // 'record_datetime' => $record['datetime']->format('Y-m-d H:i:s'),
            // 'extra'           => json_encode($record['extra']),
            'extra'           => $record['formatted'],
            'ip'              => $_SERVER['SERVER_ADDR'], // $_SERVER['REMOTE_ADDR']
            'user_agent'      => $_SERVER['HTTP_USER_AGENT'],
            'created_at'      => now(), // date('Y-m-d H:i:s')
            'type'            => 'error_log'
        ];
        DB::table('system_log')->insert($data);
        */
    }
}
