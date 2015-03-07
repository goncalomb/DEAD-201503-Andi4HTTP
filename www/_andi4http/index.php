<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require implode(DIRECTORY_SEPARATOR, array(__DIR__, 'core', 'core.php'));

echo "<!DOCTYPE html>\n";
echo '<html>';
echo '<head>';
echo '<meta charset="utf-8">';
echo '<link href="//cdn.jsdelivr.net/bootstrap/3.3.2/css/bootstrap.min.css" rel="stylesheet" type="text/css">';
echo '<title>Index of ', $url_path_clean, '</title>';
echo '<style>body { width: 750px; margin: 20px auto; }</style>';
echo '</head>';
echo '<body>';

require andi_build_dir_path(__DIR__, 'global', 'header.php');

$local_header_path = andi_build_dir_path($local_path, 'header.html');
$local_footer_path = andi_build_dir_path($local_path, 'footer.html');

if (is_file($local_header_path)) {
	echo file_get_contents($local_header_path);
}

echo '<table class="table table-condensed">';

echo '<thead>';
echo '<tr><th>Name</th><th>Last Modified</th><th>Size</th></tr>';
echo '</thead>';

echo '<tbody>';

if (count($url_path_parts)) {
	echo '<tr><td><a href="..">../</a></td></tr>';
}

andi_list_directory($local_path, function($entry, $path) {
	echo '<tr>';
	echo '<td><a href="', rawurlencode($entry), '/">', $entry, '/</a></td>';
	echo '<td>', date('d-m-Y H:i:s', filemtime($path)), '</td>';
	echo '<td>-</td>';
	echo '</tr>';
}, function($entry, $path) {
	echo '<tr>';
	echo '<td><a href="', rawurlencode($entry), '">', $entry, '</a></td>';
	echo '<td>', date('d-m-Y H:i:s', filemtime($path)), '</td>';
	echo '<td>', filesize($path), '</td>';
	echo '</tr>';
});

echo '</tbody>';
echo '</table>';

if (is_file($local_footer_path)) {
	echo file_get_contents($local_footer_path);
}

require andi_build_dir_path(__DIR__, 'global', 'footer.php');

echo '</body>';
echo '</html>';

echo "\n";

?>
