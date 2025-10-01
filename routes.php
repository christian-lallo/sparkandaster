<?php
	
	Flight::route('/', array('PageController', 'index'));

	Flight::route('/about', array('PageController', 'about'));

	Flight::route('/work/@path', array('PageController', 'work'));
	
	Flight::route('/work', array('PageController', 'workIndex'));

	
	// routes.php
	Flight::route('GET /twig-smoke', function () {
		echo "<!doctype html><meta charset='utf-8'><title>Twig OK</title><h1>Twig OK (routes.php)</h1>";
	});

	
// Simple health probe so we can confirm rewrites hit Flight
	Flight::route('GET /health', function () {
		Flight::json([
			'ok'      => true,
			'php'     => PHP_VERSION,
			'engine'  => 'Flight (routes.php)',
			'rewrite' => true,
		]);
	});
