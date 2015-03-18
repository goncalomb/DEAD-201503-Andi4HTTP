<?php

AndiHtml::start();

$bootswatch_theme = Andi::config('theme-config', 'bootswatch');

if ($bootswatch_theme && in_array($bootswatch_theme, array(
	'amelia',
	'cerulean', 'cosmo', 'cyborg',
	'darkly', 'flatly', 'journal',
	'lumen', 'paper', 'readable',
	'sandstone', 'simplex', 'slate',
	'spacelab', 'superhero','united',
	'yeti'
))) {
	AndiHtml::appendToHead('<link href="//cdn.jsdelivr.net/bootswatch/3.3.2/', $bootswatch_theme, '/bootstrap.min.css" rel="stylesheet" type="text/css">');
} else {
	AndiHtml::appendToHead('<link href="//cdn.jsdelivr.net/bootstrap/3.3.2/css/bootstrap.min.css" rel="stylesheet" type="text/css">');
}

AndiHtml::appendToHead('<style>body { max-width: 750px; margin: 20px auto; padding: 0 20px; }</style>');

andi_html_header();
andi_html_main_table('table table-condensed');
andi_html_footer();

AndiHtml::end();

?>
