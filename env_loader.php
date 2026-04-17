<?php
// =====================================================
//  env_loader.php
//  Place this file in your project ROOT folder.
//  Include it once at the top of config.php.
//  It reads your .env file and loads all keys into
//  $_ENV so you can use $_ENV['PAYSTACK_SECRET_KEY']
//  anywhere in your app.
// =====================================================

function loadEnv($filePath) {
    if (!file_exists($filePath)) {
        // .env file missing — show a clear error during development
        // In production you can change this to: return false;
        die('<b>Error:</b> .env file not found at: ' . htmlspecialchars($filePath) .
            '<br>Copy <code>.env.example</code> to <code>.env</code> and fill in your keys.');
    }

    $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    foreach ($lines as $line) {
        // Skip comment lines starting with #
        if (strpos(trim($line), '#') === 0) {
            continue;
        }

        // Must contain = to be a valid key=value pair
        if (strpos($line, '=') === false) {
            continue;
        }

        list($key, $value) = explode('=', $line, 2);

        $key   = trim($key);
        $value = trim($value);

        // Strip surrounding quotes if present  e.g. KEY="value" or KEY='value'
        if (
            (substr($value, 0, 1) === '"' && substr($value, -1) === '"') ||
            (substr($value, 0, 1) === "'" && substr($value, -1) === "'")
        ) {
            $value = substr($value, 1, -1);
        }

        // Load into $_ENV and putenv (makes it available everywhere)
        if (!array_key_exists($key, $_ENV)) {
            $_ENV[$key] = $value;
            putenv("$key=$value");
        }
    }
}

// Load the .env file from the project root
// __DIR__ = folder where env_loader.php lives (your project root)
loadEnv(__DIR__ . '/.env');
