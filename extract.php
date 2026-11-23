<?php

/**
 * InfinityFree Deployment Extractor & MySQL Provisioner (IT0049 TSA4).
 *
 * Unpacks summative4-infinityfree.zip into /htdocs, configures writable storage directories,
 * imports database/schema_export.sql using credentials from the unpacked .env file,
 * falls back cleanly to pre-seeded SQLite if MySQL provisioning fails, and removes itself.
 */
header('Content-Type: text/plain; charset=utf-8');

/**
 * Parse key=value entries from a dotenv file safely without shell execution.
 *
 * @return array<string, string>
 */
function parseDotEnv(string $path): array
{
    if (! file_exists($path)) {
        return [];
    }

    $values = [];
    foreach (file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
        $trimmed = trim($line);
        if ($trimmed === '' || str_starts_with($trimmed, '#') || ! str_contains($trimmed, '=')) {
            continue;
        }

        [$key, $val] = explode('=', $trimmed, 2);
        $values[trim($key)] = trim(trim($val), "\"'");
    }

    return $values;
}

/**
 * Execute SQL statements from a schema export while ignoring comments outside string literals.
 */
function executeSqlStatements(PDO $pdo, string $rawSql): void
{
    $buffer = '';
    $inSingleQuote = false;
    $inDoubleQuote = false;
    $length = strlen($rawSql);

    for ($i = 0; $i < $length; $i++) {
        $char = $rawSql[$i];
        $next = $i + 1 < $length ? $rawSql[$i + 1] : '';

        if (! $inSingleQuote && ! $inDoubleQuote) {
            if ($char === '-' && $next === '-') {
                $newlinePos = strpos($rawSql, "\n", $i);
                $i = $newlinePos === false ? $length : $newlinePos;

                continue;
            }
            if ($char === '/' && $next === '*') {
                $closePos = strpos($rawSql, '*/', $i + 2);
                $i = $closePos === false ? $length : $closePos + 1;

                continue;
            }
        }

        if ($char === "'" && ! $inDoubleQuote && ($i === 0 || $rawSql[$i - 1] !== '\\')) {
            $inSingleQuote = ! $inSingleQuote;
        } elseif ($char === '"' && ! $inSingleQuote && ($i === 0 || $rawSql[$i - 1] !== '\\')) {
            $inDoubleQuote = ! $inDoubleQuote;
        }

        if ($char === ';' && ! $inSingleQuote && ! $inDoubleQuote) {
            $statement = trim($buffer);
            if ($statement !== '') {
                $pdo->exec($statement);
            }
            $buffer = '';

            continue;
        }

        $buffer .= $char;
    }

    $trailing = trim($buffer);
    if ($trailing !== '') {
        $pdo->exec($trailing);
    }
}

$zipFile = __DIR__.'/summative4-infinityfree.zip';

try {
    if (! file_exists($zipFile)) {
        http_response_code(404);
        throw new RuntimeException('Deployment package not found.');
    }

    if (! class_exists('ZipArchive')) {
        http_response_code(500);
        throw new RuntimeException('ZipArchive extension is not enabled.');
    }

    $zip = new ZipArchive;
    if ($zip->open($zipFile) !== true) {
        http_response_code(500);
        throw new RuntimeException('Unable to open deployment package.');
    }

    $extracted = $zip->extractTo(__DIR__);
    $zip->close();

    if (! $extracted) {
        http_response_code(500);
        throw new RuntimeException('Failed extracting deployment package.');
    }

    @unlink($zipFile);
    echo "SUCCESS: Extracted summative4-infinityfree.zip.\n";

    if (file_exists(__DIR__.'/index2.html')) {
        @unlink(__DIR__.'/index2.html');
        echo "Removed InfinityFree placeholder index2.html.\n";
    }

    $storageDirs = [
        __DIR__.'/storage/app/public',
        __DIR__.'/storage/framework/cache/data',
        __DIR__.'/storage/framework/sessions',
        __DIR__.'/storage/framework/views',
        __DIR__.'/storage/logs',
        __DIR__.'/bootstrap/cache',
    ];
    foreach ($storageDirs as $dir) {
        if (! is_dir($dir)) {
            @mkdir($dir, 0755, true);
        }
        @chmod($dir, 0755);
    }
    echo "Verified storage and cache directories.\n";

    $env = parseDotEnv(__DIR__.'/.env');
    $sqlFile = __DIR__.'/database/schema_export.sql';
    $dbHost = $env['DB_HOST'] ?? '';
    $dbName = $env['DB_DATABASE'] ?? '';
    $dbUser = $env['DB_USERNAME'] ?? '';
    $dbPass = $env['DB_PASSWORD'] ?? '';

    $mysqlSuccess = false;
    if ($dbHost !== '' && $dbName !== '' && $dbUser !== '' && file_exists($sqlFile)) {
        try {
            $dsn = "mysql:host={$dbHost};dbname={$dbName};charset=utf8mb4";
            $pdo = new PDO($dsn, $dbUser, $dbPass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_TIMEOUT => 10,
            ]);

            executeSqlStatements($pdo, (string) file_get_contents($sqlFile));
            echo "SUCCESS: Provisioned MySQL schema and seed data.\n";
            $mysqlSuccess = true;
        } catch (Throwable) {
            echo "NOTICE: MySQL provisioning did not succeed; falling back to SQLite.\n";
        }
    }

    $envFile = __DIR__.'/.env';
    $sqliteFile = __DIR__.'/database/database.sqlite';
    if (! $mysqlSuccess && file_exists($envFile) && file_exists($sqliteFile)) {
        $envContent = (string) file_get_contents($envFile);
        $envContent = preg_replace('/^DB_CONNECTION=mysql$/m', 'DB_CONNECTION=sqlite', $envContent);
        $envContent = preg_replace('/^DB_DATABASE=.*$/m', 'DB_DATABASE='.$sqliteFile, $envContent);
        file_put_contents($envFile, $envContent);
        echo "Fallback: configured pre-seeded SQLite connection in .env.\n";
    }
} catch (Throwable $e) {
    if (http_response_code() < 400) {
        http_response_code(500);
    }
    echo 'ERROR: '.$e->getMessage()."\n";
} finally {
    if (file_exists(__FILE__)) {
        @unlink(__FILE__);
        echo "Removed extract.php.\n";
    }
}
