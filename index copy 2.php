<?php
// ---------------------------------------------
// Bootstrap / diagnostics
// ---------------------------------------------
declare(strict_types=1);
error_reporting(E_ALL);
ini_set('display_errors', '1');

$autoload = __DIR__ . '/vendor/autoload.php';
if (!file_exists($autoload)) {
    http_response_code(500);
    echo "Composer autoload not found at {$autoload}. Run 'composer install'.";
    exit;
}
require $autoload;

// ---------------------------------------------
// Emergency no-rewrite bypass so you can test
// Example: /index.php?r=health
// ---------------------------------------------
if (isset($_GET['r'])) {
    header('X-Flight-Bypass: 1');

    switch ($_GET['r']) {
        case 'health':
            header('Content-Type: application/json');
            echo json_encode([
                'ok'   => true,
                'php'  => PHP_VERSION,
                'engine' => 'Flight (bypass)'
            ]);
            exit;

        case 'twig-smoke':
            echo "<!doctype html><meta charset='utf-8'><title>Twig OK</title><h1>Twig OK (bypass)</h1>";
            exit;
    }
}

// ---------------------------------------------
// Twig setup (no dotenv; deprecation-safe)
// ---------------------------------------------
$viewsPath = __DIR__ . '/views';
if (!is_dir($viewsPath)) {
    // If you haven’t restored views yet, still let health route work later
    $viewsPath = __DIR__;
}

$loader = new \Twig_Loader_Filesystem($viewsPath);
$twig = new \Twig_Environment($loader, [
    'debug' => true,
    'cache' => false,
]);
$twig->addExtension(new \Twig_Extension_Debug());

// Optional extensions if present
if (class_exists(\manuelodelain\Twig\Extension\SvgExtension::class)) {
    // Use absolute base path to avoid deprecations / path issues
    $twig->addExtension(new \manuelodelain\Twig\Extension\SvgExtension(__DIR__ . '/dist/img/icons'));
}
if (class_exists(\app\Twig\HelperTwigExtension::class)) {
    $twig->addExtension(new \app\Twig\HelperTwigExtension());
}

// Register Twig with Flight
Flight::register('view', \Twig_Environment::class, [$loader, [
    'debug' => true,
    'cache' => false,
]], function($t) use ($twig) {
    // Ensure debug/exts mirror $twig above
    $t->addExtension(new \Twig_Extension_Debug());
    if (class_exists(\manuelodelain\Twig\Extension\SvgExtension::class)) {
        $t->addExtension(new \manuelodelain\Twig\Extension\SvgExtension(__DIR__ . '/dist/img/icons'));
    }
    if (class_exists(\app\Twig\HelperTwigExtension::class)) {
        $t->addExtension(new \app\Twig\HelperTwigExtension());
    }
});

// Make controllers available
Flight::path(__DIR__ . '/app/Controllers');

// ---------------------------------------------
// Routes
// ---------------------------------------------
if (file_exists(__DIR__ . '/routes.php')) {
    require __DIR__ . '/routes.php';
} else {
    // Minimal built-ins so you can verify rewrites
    Flight::route('GET /health', function () {
        Flight::json([
            'ok' => true,
            'php' => PHP_VERSION,
            'engine' => 'Flight',
            'rewrite' => true
        ]);
    });

    Flight::route('GET /twig-smoke', function () {
        $html = "<!doctype html><meta charset='utf-8'><title>Twig OK</title>"
              . "<h1>Twig OK (routed)</h1>";
        echo $html;
    });

    // Home: if you want Twig to render a layout later, swap this to render a view.
    Flight::route('GET /', function () {
        // Temporary: serve your static fallback to confirm assets while MVC gets wired
        $fallback = __DIR__ . '/index-rendered.html';
        if (is_file($fallback)) {
            readfile($fallback);
        } else {
            echo "<!doctype html><meta charset='utf-8'><title>Home</title><h1>Home</h1>";
        }
    });
}

// ---------------------------------------------
// Start engine
// ---------------------------------------------
Flight::start();
