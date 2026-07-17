<?php
/**
 * Database connection.
 *
 * Uses PDO so the same code works against MySQL (local XAMPP) or PostgreSQL
 * (Supabase). Switch with the 'driver' key in config.local.php.
 *
 * The old mysqli connection is gone: mysqli only speaks MySQL and has no
 * Postgres mode, so it could never reach Supabase.
 */

$configFile = __DIR__ . '/config.local.php';

if (!file_exists($configFile)) {
    http_response_code(500);
    exit('Missing config.local.php. Copy config.example.php to config.local.php and fill in your credentials.');
}

$config = require $configFile;
$driver = $config['driver'] ?? 'mysql';
$creds  = $config[$driver] ?? null;

if ($creds === null) {
    http_response_code(500);
    exit("No credentials configured for driver '{$driver}' in config.local.php.");
}

if ($driver === 'pgsql') {
    // Supabase terminates TLS at the pooler and rejects plaintext connections.
    $dsn = sprintf(
        'pgsql:host=%s;port=%d;dbname=%s;sslmode=require',
        $creds['host'],
        $creds['port'] ?? 5432,
        $creds['dbname']
    );
} else {
    $dsn = sprintf(
        'mysql:host=%s;dbname=%s;charset=utf8mb4',
        $creds['host'],
        $creds['dbname']
    );
}

try {
    $pdo = new PDO($dsn, $creds['username'], $creds['password'], [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        // Real prepared statements, so user input is never interpolated.
        PDO::ATTR_EMULATE_PREPARES   => false,
    ]);

    if ($driver === 'mysql') {
        // Postgres needs "ICNo" quoted to preserve case, but by default MySQL
        // reads "ICNo" as a string literal, not a column. ANSI_QUOTES makes
        // MySQL treat double quotes as identifier quotes too, so one query
        // works against both databases.
        $pdo->exec("SET sql_mode = 'ANSI_QUOTES'");
    }
} catch (PDOException $e) {
    // Never echo $e->getMessage() here — it contains the host and username.
    error_log('Database connection failed: ' . $e->getMessage());
    http_response_code(500);
    exit('Database connection failed. Check the error log.');
}
