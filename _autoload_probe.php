<?php
declare(strict_types=1);

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

$root = __DIR__;
$autoload = $root . '/vendor/autoload.php';

header('Content-Type: text/plain; charset=utf-8');

echo "Probe start\n";
echo "PHP: " . PHP_VERSION . "\n";
echo "Autoload exists: " . (is_file($autoload) ? "YES" : "NO") . " ($autoload)\n";

if (!is_file($autoload)) {
	exit("Missing vendor/autoload.php\n");
}
require $autoload;

$checks = [
	'Flight' => class_exists('Flight'),
	'Twig_Loader_Filesystem' => class_exists('Twig_Loader_Filesystem'),
	'Twig_Environment' => class_exists('Twig_Environment'),
	'manuelodelain\\Twig\\Extension\\SvgExtension' => class_exists('manuelodelain\\Twig\\Extension\\SvgExtension'),
	'app\\Twig\\HelperTwigExtension' => class_exists('app\\Twig\\HelperTwigExtension'),
];

foreach ($checks as $name => $ok) {
	echo str_pad($name, 45) . ': ' . ($ok ? 'OK' : 'MISSING') . "\n";
}
echo "Probe done\n";
