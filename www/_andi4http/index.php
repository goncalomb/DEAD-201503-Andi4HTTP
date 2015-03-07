<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

function andi_build_dir_path() {
	return implode(DIRECTORY_SEPARATOR, array_map(function($value) {
		return (is_array($value) ? call_user_func_array('andi_build_dir_path', $value) : $value);
	}, func_get_args()));
}

function andi_write_text_file($path, $heredoc_content) {
	file_put_contents($path, str_replace("\t", '', $heredoc_content) . "\n");
}

$main_htaccess_file = andi_build_dir_path(__DIR__, '..', '.htaccess');
if (!is_file($main_htaccess_file)) {
	andi_write_text_file($main_htaccess_file, <<<EOF
		ServerSignature Off
		DirectoryIndex /_andi4http/index.php
		DirectorySlash On
		AcceptPathInfo Off
		RewriteEngine On
		Options -MultiViews -Indexes -Includes -ExecCGI +FollowSymLinks

		AddDefaultCharset utf-8
		AddCharset utf-8 .js .css

		RewriteCond %{ENV:REDIRECT_STATUS} !^.
		RewriteCond %{REQUEST_FILENAME} -f
		RewriteCond %{REQUEST_URI} (.*/)index\.php$
		RewriteRule .? %1 [R=301,L]
EOF
	);
}

$internal_htacces = andi_build_dir_path(__DIR__, '.htaccess');
if (!is_file($internal_htacces)) {
	andi_write_text_file($internal_htacces, <<<EOF
		Order Deny,Allow
		Deny from all
		<Files index.php>
			Order Allow,Deny
			Allow from all
		</Files>
EOF
	);
}

$i = strpos($_SERVER['REQUEST_URI'], '?');
$url_path = ($i !== false ? substr($_SERVER['REQUEST_URI'], 0, $i) : $_SERVER['REQUEST_URI']);
$url_path_clean = preg_replace('/\/{2,}/', '/', $url_path);
$url_path_parts = array_map('rawurldecode', explode('/', trim($url_path_clean, '/')));
if (count($url_path_parts) == 1 && $url_path_parts[0] == '') {
	$url_path_parts = array();
}
unset($i);

$local_path = andi_build_dir_path(realpath(__DIR__ . '/..'), $url_path_parts);

echo "<!DOCTYPE html>\n";
echo '<html>';
echo '<head>';
echo '<meta charset="utf-8">';
echo '<link href="//cdn.jsdelivr.net/bootstrap/3.3.2/css/bootstrap.min.css" rel="stylesheet" type="text/css">';
echo '<title>', $url_path_clean, '</title>';
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

$handle = opendir($local_path);
if ($handle) {
	$folders = array();
	$files = array();

	while (($entry = readdir($handle)) !== false) {
		if ($entry != '.' && $entry != '..' && $entry[0] != '.' && !in_array($entry, array('_andi4http', 'index.php', 'header.html', 'footer.html'))) {
			$path = $local_path . DIRECTORY_SEPARATOR . $entry;
			if (is_dir($path)) {
				$folders[$entry] = $path;
			} else if (is_file($path)) {
				$files[$entry] = $path;
			} else if (is_link($path)) {
				$link_path = readlink($path);
				if (is_dir($link_path)) {
					$folders[$entry] = $path;
				} else if (is_file($link_path)) {
					$files[$entry] = $path;
				}
			}
		}
	}

	ksort($folders);
	ksort($files);

	if (count($url_path_parts)) {
		echo '<tr><td><a href="..">../</a></td></tr>';
	}
	foreach ($folders as $entry => $path) {
		echo '<tr>';
		echo '<td><a href="', rawurlencode($entry), '/">', $entry, '/</a></td>';
		echo '<td>', date('d-m-Y H:i:s', filemtime($path)), '</td>';
		echo '<td>-</td>';
		echo '</tr>';
	}
	foreach ($files as $entry => $path) {
		echo '<tr>';
		echo '<td><a href="', rawurlencode($entry), '">', $entry, '</a></td>';
		echo '<td>', date('d-m-Y H:i:s', filemtime($path)), '</td>';
		echo '<td>', filesize($path), '</td>';
		echo '</tr>';
	}
	closedir($handle);
}

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
