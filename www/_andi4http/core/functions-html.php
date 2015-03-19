<?php

function andi_html_title_tag() {
	$title_callback = Andi::config('title-callback');
	return '<title>' . $title_callback() . '</title>';
}

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

	echo '<thead>';
	echo '<tr>';
	echo '<th>Name</th>';
	if (Andi::config('show-mimetype') && $finfo) {
		echo '<th>MIME Type</th>';
	}
	if (Andi::config('show-mtime')) {
		echo '<th>Last Modified</th>';
	}
	if (Andi::config('show-size')) {
		echo '<th>Size</th>';
	}
	echo '</tr>';
	echo '</thead>';

	echo '<tbody>';

	if (count(Andi::urlPath(true))) {
		echo '<tr><td><a href="..">../</a></td></tr>';
	}

	andi_list_directory(Andi::localPath(), function($entry, $path) use ($finfo) {
		echo '<tr>';
		echo '<td><a href="', rawurlencode($entry), '/">', $entry, '/</a></td>';
		if (Andi::config('show-mimetype') && $finfo) {
			echo '<td>-</td>';
		}
		if (Andi::config('show-mtime')) {
			if (Andi::config('show-mtime-dir')) {
				echo '<td>', date(Andi::config('date-format'), filemtime($path)), '</td>';
			} else {
				echo '<td>-</td>';
			}
		}
		if (Andi::config('show-size')) {
			echo '<td>-</td>';
		}
		echo '</tr>';
	}, function($entry, $path) use ($finfo) {
		echo '<tr>';
		echo '<td><a href="', rawurlencode($entry), '">', $entry, '</a></td>';
		if (Andi::config('show-mimetype') && $finfo) {
			echo '<td>', finfo_file($finfo, $path), '</td>';
		}
		if (Andi::config('show-mtime')) {
			echo '<td>', date(Andi::config('date-format'), filemtime($path)), '</td>';
		}
		if (Andi::config('show-size')) {
			echo '<td>', andi_format_size(filesize($path)), '</td>';
		}
		echo '</tr>';
	}, Andi::config('exclude-entries'));

	if ($finfo) {
		finfo_close($finfo);
	}

	echo '</tbody>';
	echo '</table>';
}

?>
