<?php

echo "<!DOCTYPE html>\n";
echo '<html>';
echo '<head>';
echo '<meta charset="utf-8">';

if (isset($config['theme-config']['bootswatch']) && in_array($config['theme-config']['bootswatch'], array(
	'amelia',
	'cerulean', 'cosmo', 'cyborg',
	'darkly', 'flatly', 'journal',
	'lumen', 'paper', 'readable',
	'sandstone', 'simplex', 'slate',
	'spacelab', 'superhero','united',
	'yeti'
))) {
	echo '<link href="//cdn.jsdelivr.net/bootswatch/3.3.2/', $config['theme-config']['bootswatch'], '/bootstrap.min.css" rel="stylesheet" type="text/css">';
} else {
	echo '<link href="//cdn.jsdelivr.net/bootstrap/3.3.2/css/bootstrap.min.css" rel="stylesheet" type="text/css">';
}

echo andi_html_title_tag();
echo '<style>body { max-width: 750px; margin: 20px auto; padding: 0 20px; }</style>';
echo '</head>';
echo '<body>';

andi_html_header();
andi_html_main_table('table table-condensed');
andi_html_footer();

echo '</body>';
echo '</html>';

echo "\n";

?>
