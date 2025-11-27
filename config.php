<?php
// Database config - update these values for your environment
define('DB_HOST', '127.0.0.1');
define('DB_NAME', 'dispatch_db');
// MariaDB credentials provided by user
define('DB_USER', 'IceTheNet');
define('DB_PASS', '8xLt0H34');

function getDB(): PDO
{
    static $pdo = null;
    if ($pdo instanceof PDO) {
        return $pdo;
    }

    $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4';
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ];

    try {
        $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
    } catch (PDOException $e) {
        // In production, avoid echoing DB errors directly
        throw new RuntimeException('Database connection failed: ' . $e->getMessage());
    }

    return $pdo;
}

// Helpful helper to load classes easily if not using composer
spl_autoload_register(function ($class) {
    $file = __DIR__ . '/classes/' . $class . '.php';
    if (is_file($file)) {
        include_once $file;
    }
});

?>
