<?php
/**
 * install.php
 * Simple installer script to run the SQL migration `sql/create_users.sql`.
 * Can be run from CLI or browser. Connects to the MySQL server and executes
 * each statement in the SQL file. Safe to re-run (uses IF NOT EXISTS in SQL).
 */

declare(strict_types=1);

// Load DB constants from config if available, but don't require DB_NAME to exist.
require_once __DIR__ . '/config.php';

function out($msg)
{
    if (php_sapi_name() === 'cli') {
        echo $msg . PHP_EOL;
    } else {
        echo nl2br(htmlspecialchars($msg, ENT_QUOTES | ENT_SUBSTITUTE)) . "<br>\n";
    }
}

$sqlFile = __DIR__ . '/sql/create_users.sql';
if (!is_file($sqlFile)) {
    out("SQL file not found: $sqlFile");
    exit(1);
}

$sql = file_get_contents($sqlFile);
if ($sql === false) {
    out("Failed to read SQL file: $sqlFile");
    exit(1);
}

// Connect to MySQL server without specifying a database so CREATE DATABASE works.
$dsn = sprintf('mysql:host=%s;charset=utf8mb4', DB_HOST);
$opts = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];

try {
    $pdo = new PDO($dsn, DB_USER, DB_PASS, $opts);
} catch (Exception $e) {
    out('Connection failed: ' . $e->getMessage());
    exit(1);
}

// Split statements by semicolon. Keep it simple: split on ";\n" or ";\r\n" or ";"
// and skip empty lines/comments. This is good enough for the migration file.
$stmts = preg_split('/;\s*\r?\n/', $sql);
$successCount = 0;
$errors = [];

foreach ($stmts as $stmt) {
    $stmt = trim($stmt);
    if ($stmt === '' || strpos($stmt, '--') === 0) continue;
    try {
        $pdo->exec($stmt);
        $successCount++;
        out("OK: " . (strlen($stmt) > 80 ? substr($stmt,0,80).'...' : $stmt));
    } catch (PDOException $e) {
        // Some errors are expected on re-run (duplicate index, etc.). Log and continue.
        $errors[] = ['stmt' => $stmt, 'error' => $e->getMessage()];
        out("WARN: " . $e->getMessage());
    }
}

out("Statements executed: $successCount");
if ($errors) {
    out("Completed with " . count($errors) . " warnings/errors. See above.");
    exit(0);
}

out('Install completed successfully.');
exit(0);
