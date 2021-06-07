<?php

/**
 * Thực hiện lệnh export:
 *     php db.php export
 * 
 * Lệnh import:
 *     php db.php import
 */

// Lấy các cấu hình ở file .env
// Phải thêm tham số INI_SCANNER_RAW, nếu không giá trị mà có ký tự = thì sẽ bị lỗi
// APP_KEY hay có ký tự =
$config = parse_ini_file('.env', false, INI_SCANNER_RAW);
$host = $config['DB_HOST'];
$port = $config['DB_PORT'];
$database = $config['DB_DATABASE'];
$username = $config['DB_USERNAME'];
$password = $config['DB_PASSWORD'];

// Các câu lệnh
$filePath = 'database_backup.sql';
$exportCommand = "mysqldump -h $host -P $port -u $username -p$password $database > $filePath";
$importCommand = "mysql -h $host -P $port -u $username -p$password $database < $filePath";

// Lệnh "import" hay "export"
$command = count($argv) > 1 ? strtolower($argv[1]) : '';
if ($command == 'import') {
    system($importCommand);
} elseif ($command == 'export') {
    system($exportCommand);
} else {
    echo "Invalid command\n";
}
