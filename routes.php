<?php
// routes.php

// Basic pages (controller methods are static)
Flight::route('/',        ['PageController', 'index']);
Flight::route('/about',   ['PageController', 'about']);

// Work index (lists cases from views/work/*.html|*.twig)
Flight::route('/work',    ['PageController', 'workIndex']);

// Single work case by slug (.html or .twig), prev/next handled in controller
Flight::route('/work/@path', ['PageController', 'work']);

// Simple twig-smoke probe (HTML-only; optional)
Flight::route('GET /twig-smoke', function () {
	echo "<!doctype html><meta charset='utf-8'><title>Twig OK</title><h1>Twig OK (routes.php)</h1>";
});

// Health probe to confirm rewrites hit Flight
Flight::route('GET /health', function () {
	Flight::json([
		'ok'      => true,
		'php'     => PHP_VERSION,
		'engine'  => 'Flight (routes.php)',
		'rewrite' => true,
	]);
});

// Friendly 404 page — extends layout.html
Flight::map('notFound', function() {
	http_response_code(404);
	Flight::view()->display('errors/404.html', [
		'title' => 'Not Found — Spark + Aster',
		'page'  => 'error',
	]);
});
