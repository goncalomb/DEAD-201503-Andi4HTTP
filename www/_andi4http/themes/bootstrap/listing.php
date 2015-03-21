<?php

AndiHtml::start();

$bootswatch_theme = Andi::themeConfig('bootswatch');

if ($bootswatch_theme && in_array($bootswatch_theme, array(
	'amelia',
	'cerulean', 'cosmo', 'cyborg',
	'darkly', 'flatly', 'journal',
	'lumen', 'paper', 'readable',
	'sandstone', 'simplex', 'slate',
	'spacelab', 'superhero', 'united',
	'yeti'
))) {
	AndiHtml::addCssFile('//cdn.jsdelivr.net/bootswatch/3.3.2/' . $bootswatch_theme . '/bootstrap.min.css');
} else {
	AndiHtml::addCssFile('//cdn.jsdelivr.net/bootstrap/3.3.2/css/bootstrap.min.css');
}

AndiHtml::appendToHead('<style>body { max-width: 750px; margin: 20px auto; padding: 0 20px; }</style>');

andi_html_all('table table-condensed');

AndiHtml::end();

?>
