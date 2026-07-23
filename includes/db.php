<?php
/**
 * Deenz Organics - Database Connection Helper
 * Securely connects to the MySQL Database using PDO with environment variable configuration.
 */

// Helper to load .env file into getenv(), $_ENV, and $_SERVER
function load_env_file($path) {
    if (!file_exists($path) || !is_readable($path)) {
        return false;
    }
    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $line = trim($line);
        if (empty($line) || strpos($line, '#') === 0) {
            continue;
        }
        if (strpos($line, '=') !== false) {
            list($key, $value) = explode('=', $line, 2);
            $key = trim($key);
            $value = trim($value, " \t\n\r\0\x0B\"'");
            if (!array_key_exists($key, $_SERVER) && !array_key_exists($key, $_ENV)) {
                putenv("{$key}={$value}");
                $_ENV[$key] = $value;
                $_SERVER[$key] = $value;
            }
        }
    }
    return true;
}

// Load environment variables from .env in project root or parent directories
load_env_file(__DIR__ . '/../../.env');
load_env_file(__DIR__ . '/../.env');

// Database Configuration - From Environment Variables
if (!defined('DB_HOST')) define('DB_HOST', getenv('DB_HOST') ?: 'localhost');
if (!defined('DB_USER')) define('DB_USER', getenv('DB_USER') ?: 'deenz');
if (!defined('DB_PASS')) define('DB_PASS', getenv('DB_PASS') !== false ? getenv('DB_PASS') : 'A[kvsmv8c)TEG0QJ');
if (!defined('DB_NAME')) define('DB_NAME', getenv('DB_NAME') ?: 'deenz');

// Razorpay API Credentials - From Environment Variables
if (!defined('RAZORPAY_KEY_ID')) define('RAZORPAY_KEY_ID', getenv('RAZORPAY_KEY_ID') ?: 'rzp_test_KashOrganics12');
if (!defined('RAZORPAY_KEY_SECRET')) define('RAZORPAY_KEY_SECRET', getenv('RAZORPAY_KEY_SECRET') ?: 'sk_test_kashOrganicsSecretKeyKey');

$pdo = null;
try {
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];
    $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
} catch (\PDOException $e) {
    // DB failure handling - pdo remains null
    error_log("Database connection failed: " . $e->getMessage());
}

/**
 * CSRF Token helper for protection against Cross-Site Request Forgery.
 */
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

function verify_csrf_token($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * XSS Protection helper.
 */
function sanitize_html($data) {
    return htmlspecialchars($data ?? '', ENT_QUOTES, 'UTF-8');
}

/**
 * Universal Product Image Path Normalizer
 */
function clean_image_url($img, $context = '') {
    $img = trim($img ?? '');
    if (empty($img)) {
        if (strpos(strtolower($context), 'garlic') !== false) {
            return 'assets/images/kashmiri_garlic_main.webp';
        }
        return 'assets/images/kashmiri_walnuts_main.webp';
    }
    if (strpos($img, 'http://') === 0 || strpos($img, 'https://') === 0) {
        return $img;
    }
    
    $clean = ltrim($img, '/');
    if (strpos($clean, 'assets/images/') === 0) {
        return $clean;
    }
    if (strpos($clean, 'assets/') === 0) {
        return 'assets/images/' . basename($clean);
    }

    $filename = basename($clean);
    if (strpos($filename, 'walnut') !== false) {
        if ($filename === 'walnut_kernels.jpg' || $filename === 'walnuts.jpg' || $filename === 'walnuts.webp') {
            return 'assets/images/kashmiri_walnuts_main.webp';
        }
        return 'assets/images/' . $filename;
    }
    if (strpos($filename, 'garlic') !== false) {
        if ($filename === 'garlic_cloves.jpg' || $filename === 'garlic.jpg' || $filename === 'garlic.webp') {
            return 'assets/images/kashmiri_garlic_main.webp';
        }
        return 'assets/images/' . $filename;
    }

    return 'assets/images/' . $filename;
}
?>
