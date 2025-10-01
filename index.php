<?php
// ---------------------------------------------
// Bootstrap / diagnostics
// ---------------------------------------------
declare(strict_types=1);
error_reporting(E_ALL);
ini_set('display_errors', '1');

set_error_handler(function ($errno, $errstr) {
    if ($errno === E_DEPRECATED && strpos($errstr, 'realpath(): Passing null') !== false) {
        return true; // swallow just this one deprecation
    }
    return false; // let everything else behave normally
});


$autoload = __DIR__ . '/vendor/autoload.php';
if (!file_exists($autoload)) {
    http_response_code(500);
    echo "Composer autoload not found at {$autoload}. Run 'composer install'.";
    exit;
}
require $autoload;

// Load our PHP 8.2-safe Svg extension shim if present
$svgCompatPath = __DIR__ . '/app/Compat/SvgExtensionCompat.php';
if (is_file($svgCompatPath)) {
    require_once $svgCompatPath;
}

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
                'ok'     => true,
                'php'    => PHP_VERSION,
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

// Guarded loader to avoid realpath(null) deprecation
$loader = new \Twig_Loader_Filesystem([__DIR__]);
if (is_dir($viewsPath)) {
    $loader->addPath($viewsPath);
}

$twig = new \Twig_Environment($loader, [
    'debug' => true,
    'cache' => false,
]);
$twig->addExtension(new \Twig_Extension_Debug());

// Prefer compat class; fallback to upstream if shim not present
if (class_exists(\App\Compat\SvgExtensionCompat::class)) {
    $twig->addExtension(new \App\Compat\SvgExtensionCompat(__DIR__ . '/dist/img/icons'));
} elseif (class_exists(\manuelodelain\Twig\Extension\SvgExtension::class)) {
    $twig->addExtension(new \manuelodelain\Twig\Extension\SvgExtension(__DIR__ . '/dist/img/icons'));
}

if (class_exists(\app\Twig\HelperTwigExtension::class)) {
    $twig->addExtension(new \app\Twig\HelperTwigExtension());
}

// ---------------------------------------------
// Register Twig with Flight (guarded loader) — mirrors main instance
// ---------------------------------------------
$flightLoader = new \Twig_Loader_Filesystem([__DIR__]);
if (is_dir($viewsPath)) {
    $flightLoader->addPath($viewsPath);
}

Flight::register(
    'view',
    \Twig_Environment::class,
    [
        $flightLoader,
        [
            'debug' => true,
            'cache' => false,
        ]
    ],
    function (\Twig_Environment $t) {
        $t->addExtension(new \Twig_Extension_Debug());

        // Prefer compat shim here too
        if (class_exists(\App\Compat\SvgExtensionCompat::class)) {
            $t->addExtension(new \App\Compat\SvgExtensionCompat(__DIR__ . '/dist/img/icons'));
        } elseif (class_exists(\manuelodelain\Twig\Extension\SvgExtension::class)) {
            $t->addExtension(new \manuelodelain\Twig\Extension\SvgExtension(__DIR__ . '/dist/img/icons'));
        }

        if (class_exists(\app\Twig\HelperTwigExtension::class)) {
            $t->addExtension(new \app\Twig\HelperTwigExtension());
        }
    }
);

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
            'ok'      => true,
            'php'     => PHP_VERSION,
            'engine'  => 'Flight',
            'rewrite' => true
        ]);
    });

    Flight::route('GET /twig-smoke', function () {
        echo "<!doctype html><meta charset='utf-8'><title>Twig OK</title><h1>Twig OK (routed)</h1>";
    });

    // Home: temporary static fallback while MVC wiring is finalized
    Flight::route('GET /', function () {
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
