<?php

// The error template.

echo '<h1>', ANDI_ERROR_CODE, ' ', ANDI_ERROR_MESSAGE, '</h1>';
echo '<p>Sorry, something is not right...</p>';
echo '<p><a href="', Andi::urlBase(), '/">Go back.</a></p>';

?>
