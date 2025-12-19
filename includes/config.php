<?php
/**
 * Configuration File
 * Database and application settings
 */

// Database Configuration
define('DB_HOST', 'localhost');
define('DB_PORT', 3306);
define('DB_NAME', 'hidrodinamika_links');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

// Application Settings
define('APP_NAME', 'Hidrodinamika Link Profile');
define('APP_URL', 'http://localhost');
define('TIMEZONE', 'Asia/Jakarta');

// Session Settings
define('SESSION_LIFETIME', 86400); // 24 hours
define('SESSION_NAME', 'hidrodinamika_session');

// Security
define('BCRYPT_COST', 10);

// Paths
define('ROOT_PATH', dirname(__DIR__));
define('INCLUDES_PATH', ROOT_PATH . '/includes');
define('MODELS_PATH', ROOT_PATH . '/models');
define('ASSETS_PATH', ROOT_PATH . '/assets');

// Set timezone
date_default_timezone_set(TIMEZONE);

// Error reporting (set to 0 in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);
