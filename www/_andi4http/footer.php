<?php

// The global footer template.

echo '<hr>';
echo '<p>Powered by Andi4HTTP (A neat directory index for HTTP).</p>';
echo '<p><em>Server (', PHP_OS, ') at ', $_SERVER['HTTP_HOST'], ', ', date(Andi::config('date-format')), '.</em></p>';

?>
