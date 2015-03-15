<?php

require __DIR__ . DIRECTORY_SEPARATOR . 'functions.php';
require __DIR__ . DIRECTORY_SEPARATOR . 'functions-html.php';

// Some constants.
define('ANDI_DIR', realpath(__DIR__ . DIRECTORY_SEPARATOR . '..'));
define('ANDI_ROOT_DIR', realpath(ANDI_DIR . DIRECTORY_SEPARATOR . '..'));
define('ANDI_CORE_DIR', __DIR__);
define('ANDI_GLOBAL_DIR', ANDI_DIR . DIRECTORY_SEPARATOR . 'global');
define('ANDI_THEMES_DIR', ANDI_DIR . DIRECTORY_SEPARATOR . 'themes');
define('ANDI_CONFIG_FILE', ANDI_DIR . DIRECTORY_SEPARATOR . 'config.php');

// Class autoloader.
spl_autoload_register(function($class_name) {
	require __DIR__ . DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR . 'class.' .  $class_name . '.php';
});

Andi::initialize();

// Process the URL.
$i = strpos($_SERVER['REQUEST_URI'], '?');
$url_path = ($i !== false ? substr($_SERVER['REQUEST_URI'], 0, $i) : $_SERVER['REQUEST_URI']);
unset($i);
$url_path_clean = preg_replace('/\/{2,}/', '/', $url_path);
$url_path_parts = array_map('rawurldecode', explode('/', trim($url_path_clean, '/')));
if (count($url_path_parts) == 1 && $url_path_parts[0] == '') {
	$url_path_parts = array();
}
$local_path = andi_build_dir_path(ANDI_ROOT_DIR, $url_path_parts);
// TODO: This only works if the root of the domain is the same as the root for the listing.
// These variables exist is the global scope:
// $url_path       = Requested URL
// $url_path_clean = Requested URL without duplicate slashes
// $url_path_parts = Array with clean URL parts
// $local_path     = Filesystem path that maps to the requested URL

// Create main .htaccess that lives on the root directory.
$main_htaccess_file = ANDI_ROOT_DIR . DIRECTORY_SEPARATOR . '.htaccess';
if (!is_file($main_htaccess_file)) {
	andi_write_text_file($main_htaccess_file, <<<EOF
		ServerSignature Off
		DirectoryIndex /_andi4http/core/listing.php
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
unset($main_htaccess_file);

// TODO: Use less .htaccess files?

// Create internal .htaccess, it controls the access to the _andi4http directory.
$internal_htacces_file = ANDI_DIR . DIRECTORY_SEPARATOR . '.htaccess';
if (!is_file($internal_htacces_file)) {
	andi_write_text_file($internal_htacces_file, <<<EOF
		Order Deny,Allow
		Deny from all
EOF
	);
}
$internal_htacces_file = ANDI_CORE_DIR . DIRECTORY_SEPARATOR . '.htaccess';
if (!is_file($internal_htacces_file)) {
	andi_write_text_file($internal_htacces_file, <<<EOF
		<Files listing.php>
			Order Allow,Deny
			Allow from all
		</Files>
EOF
	);
}
unset($internal_htacces_file);

?>
