<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require __DIR__ . DIRECTORY_SEPARATOR . 'core.php';

echo "<!DOCTYPE html>\n";
echo '<html>';
echo '<head>';
echo '<meta charset="utf-8">';
echo '<link href="//cdn.jsdelivr.net/bootstrap/3.3.2/css/bootstrap.min.css" rel="stylesheet" type="text/css">';
echo '<title>Index of ', $url_path_clean, '</title>';
echo '<style>body { width: 750px; margin: 20px auto; }</style>';
echo '</head>';
echo '<body>';

andi_html_header();
andi_html_main_table('table table-condensed');
andi_html_footer();

echo '</body>';
echo '</html>';

echo "\n";

?>
