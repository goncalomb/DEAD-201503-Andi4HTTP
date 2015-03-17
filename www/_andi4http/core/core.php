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

// Create main .htaccess that lives on the root directory.
andi_write_htaccess(ANDI_ROOT_DIR . DIRECTORY_SEPARATOR . '.htaccess', '
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
');

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
