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

	public static function work( $path ) {

		Flight::view()->display('work/' . $path . '.html');
	}
}

