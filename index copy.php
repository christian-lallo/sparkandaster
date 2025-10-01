<?php
/**
 * App bootstrap
 * - Removes Dotenv (no .env required)
 * - Initializes Twig + Flight
 * - PHP 8–friendly
 */

error_reporting(E_ALL);
ini_set('display_errors', '1');

require __DIR__ . '/vendor/autoload.php';

use manuelodelain\Twig\Extension\SvgExtension;
use flight\Engine;

// Optional: your custom extension, guarded if it’s not present.
$helperTwigExtensionClass = 'app\\Twig\\HelperTwigExtension';

// ---- Resolve paths ----
$viewsPath = __DIR__ . '/views';
if (!is_dir($viewsPath)) {
    http_response_code(500);
    echo 'Views path not found: ' . htmlspecialchars($viewsPath);
    exit;
}

// ---- Compute a robust base URL for templates ----
$https = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] === '443');
$scheme = $https ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
$scriptDir = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/')), '/');
$baseUrl = $scheme . '://' . $host . ($scriptDir ? $scriptDir . '/' : '/');

// ---- Init Twig and register with Flight ----
$loader = new \Twig_Loader_Filesystem($viewsPath);

$twigConfig = [
    // 'cache' => __DIR__ . '/cache/twig', // enable if/when you want caching
    'debug' => true,
];

Flight::register('view', '\Twig_Environment', [$loader, $twigConfig], function (\Twig_Environment $twig) use ($baseUrl, $helperTwigExtensionClass) {
    // Debug tools
    $twig->addExtension(new \Twig_Extension_Debug());

    // Inline SVGs from this directory (adjust if your icons live elsewhere)
    $twig->addExtension(new SvgExtension('dist/img/icons'));

    // Add your custom extension if it exists
    if (class_exists($helperTwigExtensionClass)) {
        $twig->addExtension(new $helperTwigExtensionClass());
    }

    // Handy global for building absolute asset/route URLs in templates
    $twig->addGlobal('base_url', $baseUrl);
});

// Provide a tiny helper so controllers can do: Flight::render('template.twig', $data);
Flight::map('render', function (string $template, array $data = []) {
    Flight::view()->display($template, $data);
});

// ---- Flight config ----
Flight::set('flight.log_errors', true);

// ---- Controllers path ----
Flight::path(__DIR__ . '/app/Controllers');

// ---- Routes ----
require __DIR__ . '/routes.php';

// ---- Go! ----
Flight::start();
