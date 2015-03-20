<?php

function andi_html_header_global() {
	andi_require_if_exists(ANDI_GLOBAL_DIR . DIRECTORY_SEPARATOR . 'header.php');
}

function andi_html_header_local() {
	andi_require_if_exists(Andi::localPath() . DIRECTORY_SEPARATOR . 'header.html');
}

function andi_html_footer_global() {
	andi_require_if_exists(ANDI_GLOBAL_DIR . DIRECTORY_SEPARATOR . 'footer.php');
}

function andi_html_footer_local() {
	andi_require_if_exists(Andi::localPath() . DIRECTORY_SEPARATOR . 'footer.html');
}

function andi_html_main_table($table_class='') {
	echo '<table class="', $table_class, '">';

	$finfo = null;
	if (Andi::config('show-mimetype')) {
		$finfo = finfo_open(FILEINFO_MIME_TYPE | FILEINFO_PRESERVE_ATIME);
	}

	echo '<colgroup>';
	echo '<col class="col-name">';
	if (Andi::config('show-mimetype') && $finfo) {
		echo '<col class="col-mimetype">';
	}
	if (Andi::config('show-mtime')) {
		echo '<col class="col-mtime">';
	}
	if (Andi::config('show-size')) {
		echo '<col class="col-size">';
	}
	echo '</colgroup>';

	echo '<thead>';
	echo '<tr>';
	echo '<th class="th-name">Name</th>';
	if (Andi::config('show-mimetype') && $finfo) {
		echo '<th class="th-mimetype">MIME Type</th>';
	}
	if (Andi::config('show-mtime')) {
		echo '<th class="th-mtime">Last Modified</th>';
	}
	if (Andi::config('show-size')) {
		echo '<th class="th-size">Size</th>';
	}
	echo '</tr>';
	echo '</thead>';

	echo '<tbody>';

	if (count(Andi::urlPath(true))) {
		echo '<tr><td><a href="..">../</a></td></tr>';
	}

	andi_list_directory(Andi::localPath(), function($entry, $path) use ($finfo) {
		echo '<tr class="tr-dir">';
		echo '<td class="td-name"><a href="', rawurlencode($entry), '/">', $entry, '/</a></td>';
		if (Andi::config('show-mimetype') && $finfo) {
			echo '<td class="td-mimetype">-</td>';
		}
		if (Andi::config('show-mtime')) {
			if (Andi::config('show-mtime-dir')) {
				echo '<td class="td-mtime">', date(Andi::config('date-format'), filemtime($path)), '</td>';
			} else {
				echo '<td class="td-mtime">-</td>';
			}
		}
		if (Andi::config('show-size')) {
			echo '<td class="td-size">-</td>';
		}
		echo '</tr>';
	}, function($entry, $path) use ($finfo) {
		echo '<tr class="tr-file">';
		echo '<td class="td-name"><a href="', rawurlencode($entry), '">', $entry, '</a></td>';
		if (Andi::config('show-mimetype') && $finfo) {
			echo '<td class="td-mimetype">', finfo_file($finfo, $path), '</td>';
		}
		if (Andi::config('show-mtime')) {
			echo '<td class="td-mtime">', date(Andi::config('date-format'), filemtime($path)), '</td>';
		}
		if (Andi::config('show-size')) {
			echo '<td class="td-size">', andi_format_size(filesize($path)), '</td>';
		}
		echo '</tr>';
	}, Andi::config('exclude-entries'));

	if ($finfo) {
		finfo_close($finfo);
	}

	echo '</tbody>';
	echo '</table>';
}

function andi_html_all($main_table_class='') {
	echo '<header>';
	andi_html_header_global();
	andi_html_header_local();
	echo '</header>';
	echo '<main>';
	andi_html_main_table($main_table_class);
	echo '</main>';
	echo '<footer>';
	andi_html_footer_local();
	andi_html_footer_global();
	echo '</footer>';
}

?>
