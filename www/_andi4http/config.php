<?php

// Date format ('http://php.net/manual/en/function.date.php).
$config['date-format'] = 'd-m-Y H:i:s';

// Enable/Disable the JSON API (/?json=true).
$config['json-api'] = true;

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
