<?php

// Initialize the AndiHtml class and start the page.
AndiHtml::start();
// All the output is buffered and will be used to populate the body tag.

// To append something to the head, like a internal style sheet, use:
// AndiHtml::appendToHead('some html', 'some more html', 'bla bla', ...);
AndiHtml::appendToHead('<style>
body {
	font-family: sans-serif;
	text-align: center;
	color: forestgreen;
}
a {
	color: inherit;
}
a:hover {
	display: inline-block;
	transform: scale(1, -1);
}
header {
	color: tomato;
}
table {
	margin: 0 auto;
	text-align: right;
}
table tbody {
	color: olive;
}
table .th-name, table .td-name {
	text-align: left;
}
table .col-mtime {
	background-color: whitesmoke;
}
footer {
	color: salmon;
}
</style>');

// You may want to link to some external style sheet or script file:
// AndiHtml::addCssFile('/my-style-file.css');
// AndiHtml::addJsFile('/my-javascript-file.js');

// A custom page title.
AndiHtml::setTitle('My Theme ROCKS! - ' . Andi::localPath());

// Output all the main HTML code.
// This includes local and global headers/footers and the main table.
andi_html_all();

// Maybe add some HTML code in the end.
echo '<p>Smoked salmon salad with tomato and olives...</p>';

// Output and end the page, nothing after will be executed.
AndiHtml::end();

?>
