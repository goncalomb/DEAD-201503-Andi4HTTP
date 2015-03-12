<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require __DIR__ . DIRECTORY_SEPARATOR . 'core.php';

if (isset($_GET['json']) && $config['json-api']) {
	// JSON api.
	$host = $_SERVER['HTTP_HOST'];
	$query = $_SERVER['QUERY_STRING'];
	$data = array(
		'url' => "{$host}{$url_path_clean}?{$query}",
		'entries' => array(),
		'version' => 0
	);

	andi_list_directory($local_path, function($entry, $path) use ($url_path_clean, $host, $query, &$data) {
		$data['entries'][] = array(
			'name' => $entry,
			'url' => "{$host}{$url_path_clean}{$entry}/?{$query}",
			'type' => 'dir',
			'mtime' => filemtime($path)
		);
	}, function($entry, $path) use ($url_path_clean, $host, $query, &$data) {
		$data['entries'][] = array(
			'name' => $entry,
			'url' => "{$host}{$url_path_clean}{$entry}",
			'type' => 'file',
			'mtime' => filemtime($path),
			'size' => filesize($path)
		);
	}, $config['exclude-entries']);

	header('Content-Type: application/json');
	echo json_encode($data, JSON_PRETTY_PRINT);
	exit();
}

$theme_file = andi_build_dir_path(ANDI_THEMES_DIR, $config['theme'], 'listing.php');
if ($config['theme'] != 'default' && is_file($theme_file)) {
	require $theme_file;
} else {
	echo "<!DOCTYPE html>\n";
	echo '<html>';
	echo '<head>';
	echo '<meta charset="utf-8">';
	echo '<title>Index of ', $url_path_clean, '</title>';
	echo '<style>body { font-family: Arial; max-width: 750px; margin: 20px auto; padding: 0 20px; } table { width: 100%; border-spacing: 0; border-collapse: collapse; } table thead th { border-bottom: 2px solid #d2d2d2; } table td { padding: 5px; border-bottom: 1px solid #d2d2d2; }</style>';
	echo '</head>';
	echo '<body>';

	andi_html_header();
	andi_html_main_table();
	andi_html_footer();

	echo '</body>';
	echo '</html>';

	echo "\n";
}

?>
