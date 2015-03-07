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
echo '<title>', $url_path_clean, '</title>';
echo '</head>';
echo '<body>';

echo '<pre>';

echo $url_path_clean, "\n";
echo $local_path, "\n";

echo "\n";

$handle = opendir($local_path);
if ($handle) {
	$folders = array();
	$files = array();

	while (($entry = readdir($handle)) !== false) {
		if ($entry != '.' && $entry != '..' && $entry[0] != '.' && $entry != '_andi4http') {
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
		echo "<a href=\"..\">../</a>\n";
	}
	foreach ($folders as $entry => $path) {
		echo '<a href="', rawurlencode($entry), '/">', $entry, "/</a>\n";
	}
	foreach ($files as $entry => $path) {
		if ($entry != 'index.php') {
			echo '<a href="', rawurlencode($entry), '">', $entry, "</a>\n";
		}
	}
	closedir($handle);
}

echo "\nPowered by Andi 4 HTTP (A neat directory index for HTTP).\n";

echo '</pre>';

echo '</body>';
echo '</html>';

echo "\n";

?>
