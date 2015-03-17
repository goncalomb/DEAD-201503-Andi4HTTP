<?php

function andi_html_title_tag() {
	$title_callback = Andi::config('title-callback');
	return '<title>' . $title_callback() . '</title>';
}

function andi_html_header() {
	// Global header.
	require andi_build_dir_path(ANDI_GLOBAL_DIR, 'header.php');
	// Local header.
	$local_header_path = andi_build_dir_path(Andi::localPath(), 'header.html');
	if (is_file($local_header_path)) {
		echo file_get_contents($local_header_path);
	}
}

function andi_html_footer() {
	// Local footer.
	$local_footer_path = andi_build_dir_path(Andi::localPath(), 'footer.html');
	if (is_file($local_footer_path)) {
		echo file_get_contents($local_footer_path);
	}
	// Global footer.
	require andi_build_dir_path(ANDI_GLOBAL_DIR, 'footer.php');
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
