<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require __DIR__ . DIRECTORY_SEPARATOR . 'core.php';

if (isset($_GET['json']) && Andi::config('json-api')) {
	// JSON api.
	$url_path = Andi::urlPath();

	$host = $_SERVER['HTTP_HOST'];
	$query = $_SERVER['QUERY_STRING'];
	$data = array(
		'url' => "{$host}{$url_path}?{$query}",
		'entries' => array(),
		'version' => 0
	);

	andi_list_directory(Andi::localPath(), function($entry, $path) use ($url_path, $host, $query, &$data) {
		$data['entries'][] = array(
			'name' => $entry,
			'url' => "{$host}{$url_path}{$entry}/?{$query}",
			'type' => 'dir',
			'mtime' => filemtime($path)
		);
	}, function($entry, $path) use ($url_path, $host, $query, &$data) {
		$data['entries'][] = array(
			'name' => $entry,
			'url' => "{$host}{$url_path}{$entry}",
			'type' => 'file',
			'mtime' => filemtime($path),
			'size' => filesize($path)
		);
	}, Andi::config('exclude-entries'));

	header('Content-Type: application/json');
	echo json_encode($data, JSON_PRETTY_PRINT);
	exit();
}

// Include the theme.
$theme = Andi::theme();
$theme_file = andi_build_dir_path(ANDI_THEMES_DIR, $theme, 'listing.php');
if ($theme != 'default' && $theme != 'plain' && is_file($theme_file)) {
	require $theme_file;
	exit();
}

// No custom theme? Will use a harcoded one...

AndiHtml::start();

if ($theme != 'plain') {
	AndiHtml::appendToHead('<style>
body {
	font-family: sans-serif;
	max-width: 750px;
	margin: 20px auto;
	padding: 0 20px;
}
table {
	width: 100%;
	border-spacing: 0;
	border-collapse: collapse;
}
table thead th {
	padding: 5px;
	border-bottom: 2px solid #d2d2d2;
	text-align: left;
}
table td {
	padding: 5px;
	border-bottom: 1px solid #d2d2d2;
}
</style>');
}

andi_html_all();

AndiHtml::end();

?>
