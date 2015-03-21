<?php

/*
  Available out-of-the-box themes:
    default   : The default hardcoded theme (also used as fallback).
    plain     : Simple theme without any styling. You can easily customize it
                using the header.php file (see that file for more details).
    example   : Example theme with comments to help you creating new themes.
    bootstrap : Bootstrap based theme.
*/
$config['theme'] = 'bootstrap';

// Bootstrap theme specific configs.
$config['theme-config']['bootstrap'] = array(
	// Bootswatch theme, possible values:
	// amelia, cerulean, cosmo, cyborg, darkly, flatly, journal, lumen, paper,
	// readable, sandstone, simplex, slate, spacelab, superhero, united, yeti
	// and none (to use the default Bootstrap style).
	'bootswatch' => 'readable'
);

// You can always tweak the style in the header.php file.

// Date format (http://php.net/manual/en/function.date.php).
$config['date-format'] = 'd-m-Y H:i:s';

// Enable/Disable the JSON API (/?json=true).
$config['json-api'] = true;

// Show/Hide breadcrumbs.
$config['breadcrumbs'] = true;
$config['breadcrumbs-on-root'] = false;

// Show/Hide parent link (../).
$config['parent-link'] = false;

// Show/Hide main table columns.
$config['show-mimetype'] = false;
$config['show-mtime'] = true;
$config['show-mtime-dir'] = true;
$config['show-size'] = true;

// All entries set here will not be listed, they may still be accessible.
// An .htaccess file prevents '_andi4http' from being accessible.
$config['exclude-entries'] = array(
	'_andi4http',
	'index.php',
	'header.html',
	'footer.html'
);

?>
