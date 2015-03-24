<?php

// Time constants.
define('ANDI_MICROTIME', (
	isset($_SERVER['REQUEST_TIME_FLOAT']) ?
	(float) $_SERVER['REQUEST_TIME_FLOAT'] :
	microtime(true)
));
define('ANDI_TIME', floor(ANDI_MICROTIME));

require __DIR__ . DIRECTORY_SEPARATOR . 'functions.php';
require __DIR__ . DIRECTORY_SEPARATOR . 'functions-html.php';

// Some constants.
define('ANDI_DIR', realpath(__DIR__ . DIRECTORY_SEPARATOR . '..'));
define('ANDI_ROOT_DIR', realpath(ANDI_DIR . DIRECTORY_SEPARATOR . '..'));
define('ANDI_CORE_DIR', __DIR__);
define('ANDI_THEMES_DIR', ANDI_DIR . DIRECTORY_SEPARATOR . 'themes');
define('ANDI_CONFIG_FILE', ANDI_DIR . DIRECTORY_SEPARATOR . 'config.php');

// Error constants.
$error_messages = array(
	200 => 'OK',
	400 => 'Bad Request',
	401 => 'Unauthorized',
	403 => 'Forbidden',
	404 => 'Not Found',
	405 => 'Method Not Allowed',
	500 => 'Internal Server Error',
	501 => 'Not Implemented',
	503 => 'Service Temporarily Unavailable'
);
$error_code = (isset($_SERVER['REDIRECT_STATUS']) ? (int) $_SERVER['REDIRECT_STATUS'] : 200);
define('ANDI_ERROR_CODE', (isset($error_messages[$error_code]) ? $error_code : 500));
define('ANDI_ERROR_MESSAGE', $error_messages[ANDI_ERROR_CODE]);
unset($error_messages);
unset($error_code);

// Class autoloader.
spl_autoload_register(function($class_name) {
	require __DIR__ . DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR . 'class.' .  $class_name . '.php';
});

Andi::initialize();

// Create main .htaccess that lives on the root directory.
$main_listing_file = Andi::urlBase() . '/_andi4http/core/listing.php';
andi_write_htaccess(ANDI_ROOT_DIR . DIRECTORY_SEPARATOR . '.htaccess', '
ServerSignature Off
DirectoryIndex ' . $main_listing_file . '
DirectorySlash On
AcceptPathInfo Off
RewriteEngine On
Options -MultiViews -Indexes -Includes -ExecCGI +FollowSymLinks

ErrorDocument 400 ' . $main_listing_file . '
ErrorDocument 401 ' . $main_listing_file . '
ErrorDocument 403 ' . $main_listing_file . '
ErrorDocument 404 ' . $main_listing_file . '
ErrorDocument 405 ' . $main_listing_file . '
ErrorDocument 500 ' . $main_listing_file . '
ErrorDocument 501 ' . $main_listing_file . '
ErrorDocument 503 ' . $main_listing_file . '

AddDefaultCharset utf-8
AddCharset utf-8 .js .css

RewriteCond %{ENV:REDIRECT_STATUS} !^.
RewriteCond %{REQUEST_FILENAME} -f
RewriteCond %{REQUEST_URI} (.*/)index\.php$
RewriteRule .? %1 [R=301,L]
');
unset($main_listing_file);

// TODO: Use less .htaccess files?

// Create internal .htaccess, it controls the access to the _andi4http directory.
andi_write_htaccess(ANDI_DIR . DIRECTORY_SEPARATOR . '.htaccess', '
Order Deny,Allow
Deny from all
');
andi_write_htaccess(ANDI_CORE_DIR . DIRECTORY_SEPARATOR . '.htaccess', '
<Files listing.php>
	Order Allow,Deny
	Allow from all
</Files>
');

?>
