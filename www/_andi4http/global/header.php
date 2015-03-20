<?php

// The global header template.

/*
  Custom style your listing (without a new theme):

  Because all the HTML is buffered, you can also use this file (or the
  footer.php) to style the listing without creating a new theme.

  You may want to set the theme to 'plain', this will output HTML without any
  style. Change in the config.php file:
  $config['theme'] = 'plain';

  Use the AndiHtml class here to apply your style changes, e.g.:
  AndiHtml::addCssFile('/my-style-file.css');
  AndiHtml::appendToHead('<style>body { background-color: teal; }</style>');
  AndiHtml::setTitle('My Listing - ' . Andi::localPath());
*/

echo '<h1>Index of ', Andi::urlPath(), '</h1>';

?>
