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
	});

	header('Content-Type: application/json');
	echo json_encode($data, JSON_PRETTY_PRINT);
	exit();
}

echo "<!DOCTYPE html>\n";
echo '<html>';
echo '<head>';
echo '<meta charset="utf-8">';
echo '<link href="//cdn.jsdelivr.net/bootstrap/3.3.2/css/bootstrap.min.css" rel="stylesheet" type="text/css">';
echo '<title>Index of ', $url_path_clean, '</title>';
echo '<style>body { width: 750px; margin: 20px auto; }</style>';
echo '</head>';
echo '<body>';

andi_html_header();
andi_html_main_table('table table-condensed');
andi_html_footer();

echo '</body>';
echo '</html>';

echo "\n";

?>
