<?php

class PageController {

	public function __construct() {}

	public static function index() {
		$data = [
			'header' => [
				'title' => '<span class="is-color-pink">Welcome</span>',
				'body'  => 'Spark + Aster is a branding and marketing agency built on the belief that people don’t want to be persuaded, they want to be understood. And when people feel understood, great things happen.'
			]
		];
		Flight::view()->display('index.html', ['data' => $data]);
	}

	public static function about() {
		$data = [
			'header' => [
				'title' => 'We create <span class="is-color-pink">ideas.</span>',
				'body'  => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad.'
			]
		];
		Flight::view()->display('about.html', ['data' => $data]);
	}

	// /work index — lists all case templates in views/work/*.html or *.twig
	public static function workIndex() {
		// Resolve views root
		$viewsRoot = Flight::get('flight.views.path');
		if (!$viewsRoot) { $viewsRoot = __DIR__ . '/views'; }
		$viewsRoot = rtrim($viewsRoot, '/\\');
		$dir = $viewsRoot . '/work';
	
		$flat  = array_merge(glob($dir . '/*.html') ?: [], glob($dir . '/*.twig') ?: []);
		$index = array_merge(glob($dir . '/*/index.html') ?: [], glob($dir . '/*/index.twig') ?: []);
		$all   = array_merge($flat, $index);
	
		$seen  = [];
		$cases = [];
		foreach ($all as $f) {
			$slug = pathinfo($f, PATHINFO_FILENAME);
			if ($slug === 'index') { $slug = basename(dirname($f)); }
			if (isset($seen[$slug])) continue;
			$seen[$slug] = true;
			$cases[] = ['slug' => $slug, 'title' => ucwords(str_replace('-', ' ', $slug))];
		}
		usort($cases, function($a,$b){ return strcmp($a['title'], $b['title']); });
	
		Flight::view()->display('work/index.html', [
			'title' => 'Work — Spark + Aster',
			'page'  => 'work',
			'cases' => $cases,
		]);
	}

	// /work/@path — render a single case by slug (.html or .twig) with prev/next
	public static function work($path) {
		// Resolve views root
		$viewsRoot = Flight::get('flight.views.path');
		if (!$viewsRoot) { $viewsRoot = __DIR__ . '/views'; }
		$viewsRoot = rtrim($viewsRoot, '/\\');
		$dir = $viewsRoot . '/work';
	
		$path = trim(trim($path), '/');
	
		// Try flat, then folder index
		$candidates = [
			$dir . '/' . $path . '.html',
			$dir . '/' . $path . '.twig',
			$dir . '/' . $path . '/index.html',
			$dir . '/' . $path . '/index.twig',
		];
	
		$template = null;
		foreach ($candidates as $cand) {
			if (file_exists($cand)) {
				// Make template name relative to views root
				$template = ltrim(str_replace($viewsRoot, '', $cand), '/\\');
				break;
			}
		}
	
		// Case-insensitive fallback across both styles
		if (!$template) {
			$all = array_merge(
				glob($dir . '/*.html') ?: [],
				glob($dir . '/*.twig') ?: [],
				glob($dir . '/*/index.html') ?: [],
				glob($dir . '/*/index.twig') ?: []
			);
			foreach ($all as $f) {
				$slug = pathinfo($f, PATHINFO_FILENAME);
				if ($slug === 'index') { $slug = basename(dirname($f)); }
				if (strcasecmp($slug, $path) === 0) {
					$template = ltrim(str_replace($viewsRoot, '', $f), '/\\');
					break;
				}
			}
		}
	
		if (!$template) { Flight::notFound(); return; }
	
		// Build prev/next
		$all = array_merge(
			glob($dir . '/*.html') ?: [],
			glob($dir . '/*.twig') ?: [],
			glob($dir . '/*/index.html') ?: [],
			glob($dir . '/*/index.twig') ?: []
		);
	
		$slugs = [];
		foreach ($all as $f) {
			$slug = pathinfo($f, PATHINFO_FILENAME);
			if ($slug === 'index') { $slug = basename(dirname($f)); }
			$slugs[$slug] = true;
		}
		$list = array_keys($slugs);
		sort($list, SORT_NATURAL | SORT_FLAG_CASE);
	
		$i    = array_search($path, $list, true);
		$prev = ($i !== false && $i > 0) ? $list[$i - 1] : null;
		$next = ($i !== false && $i < count($list) - 1) ? $list[$i + 1] : null;
	
		Flight::view()->display($template, [
			'title' => 'Work — ' . ucwords(str_replace('-', ' ', $path)),
			'page'  => 'work',
			'slug'  => $path,
			'prev'  => $prev,
			'next'  => $next,
		]);
	}
}
