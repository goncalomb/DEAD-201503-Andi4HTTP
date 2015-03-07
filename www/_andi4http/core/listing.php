<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require __DIR__ . DIRECTORY_SEPARATOR . 'core.php';

echo "<!DOCTYPE html>\n";
echo '<html>';
echo '<head>';
echo '<meta charset="utf-8">';
echo '<link href="//cdn.jsdelivr.net/bootstrap/3.3.2/css/bootstrap.min.css" rel="stylesheet" type="text/css">';
echo '<title>Index of ', $url_path_clean, '</title>';
echo '<style>body { width: 750px; margin: 20px auto; }</style>';
echo '</head>';
echo '<body>';

require andi_build_dir_path(ANDI_GLOBAL_DIR, 'header.php');

$local_header_path = andi_build_dir_path($local_path, 'header.html');
$local_footer_path = andi_build_dir_path($local_path, 'footer.html');

if (is_file($local_header_path)) {
	echo file_get_contents($local_header_path);
}

echo '<table class="table table-condensed">';

echo '<thead>';
echo '<tr>';
echo '<th>Name</th>';
if ($config['show-mtime']) {
	echo '<th>Last Modified</th>';
}
if ($config['show-size']) {
	echo '<th>Size</th>';
}
echo '</tr>';
echo '</thead>';

echo '<tbody>';

if (count($url_path_parts)) {
	echo '<tr><td><a href="..">../</a></td></tr>';
}

andi_list_directory($local_path, function($entry, $path) {
	global $config;
	echo '<tr>';
	echo '<td><a href="', rawurlencode($entry), '/">', $entry, '/</a></td>';
	if ($config['show-mtime']) {
		if ($config['show-mtime-dir']) {
			echo '<td>', date($config['date-format'], filemtime($path)), '</td>';
		} else {
			echo '<td>-</td>';
		}
	}
	if ($config['show-size']) {
		echo '<td>-</td>';
	}
	echo '</tr>';
}, function($entry, $path) {
	global $config;
	echo '<tr>';
	echo '<td><a href="', rawurlencode($entry), '">', $entry, '</a></td>';
	if ($config['show-mtime']) {
		echo '<td>', date($config['date-format'], filemtime($path)), '</td>';
	}
	if ($config['show-size']) {
		echo '<td>', andi_format_size(filesize($path)), '</td>';
	}
	echo '</tr>';
});

echo '</tbody>';
echo '</table>';

if (is_file($local_footer_path)) {
	echo file_get_contents($local_footer_path);
}

require andi_build_dir_path(ANDI_GLOBAL_DIR, 'footer.php');

echo '</body>';
echo '</html>';

echo "\n";

?>
