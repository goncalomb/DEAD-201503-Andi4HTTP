<?php

// This script doesn't run on the global scope!

global $config;
echo '<p>Powered by Andi4HTTP (A neat directory index for HTTP).</p>';
echo '<p><em>Server (', PHP_OS, ') at ', $_SERVER['HTTP_HOST'], ', ', date($config['date-format']), '.</em></p>';

?>
