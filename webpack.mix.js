let mix                = require('laravel-mix');
let glob               = require('import-glob-loader');
let SVGSpritemapPlugin = require('svg-spritemap-webpack-plugin');
let CopyPlugin         = require('copy-webpack-plugin');


// Variables
//––––––––––––––––––––––––––––––––––––––––––––––––––
let config = {
   assetPath  : 'src',
   publicPath : 'dist',
   env        : 'development',
   url        : 'http://sparkandaster.test'
};


// Browser Options
//––––––––––––––––––––––––––––––––––––––––––––––––––

mix.options({
	processCssUrls: false,
	postCss: [
		require('autoprefixer')({
			grid: true,
			browsers: ['last 2 versions', 'ie >= 11']
		})
	]
});

// JS
//––––––––––––––––––––––––––––––––––––––––––––––––––

mix.js(config.assetPath + '/js/scripts.js', config.publicPath + '/js');

// CSS
//––––––––––––––––––––––––––––––––––––––––––––––––––

mix.sass(config.assetPath + '/scss/styles.scss', config.publicPath + '/css')
	.sass(config.assetPath + '/scss/print.scss', config.publicPath + '/css');

// Mix Options
//––––––––––––––––––––––––––––––––––––––––––––––––––

mix.setPublicPath(config.publicPath);

mix.disableSuccessNotifications();

// Browser Sync
//––––––––––––––––––––––––––––––––––––––––––––––––––

mix.browserSync({
	open: false,
   proxy : config.url,
	https : false,
	notify: false,
	files : [
		config.publicPath + '/js/**/*',
		config.publicPath + '/css/**/*',
		{
			match: [ 
				'*.php',
				'*.vue'
			]
		}
	]
});

// Config / Plugins
//––––––––––––––––––––––––––––––––––––––––––––––––––

mix.webpackConfig({
	module: {
		rules: [
			{
				test: /\.scss/,
				loader: 'import-glob-loader'
			},
			// {
			// 	test: /\.js$/,
			// 	exclude: /node_modules/,
			// 	loader: 'babel-loader'
			// }
		]
	},
	plugins: [
		new CopyPlugin([
			{ from: 'src/img', to: 'img' }
		]),
		new SVGSpritemapPlugin({
			src     : config.assetPath + '/img/icons/*.svg',
			filename: 'img/icons.svg',
			prefix  : '',
			svgo    : {
				plugins: [
					{removeTitle: true},
					{removeUselessStrokeAndFill: true},
					{collapseGroups: true},
					{convertColors: {currentColor: true}},
					{cleanupIDs: {minify: false}}
				]
			}
		}),
	]
});
