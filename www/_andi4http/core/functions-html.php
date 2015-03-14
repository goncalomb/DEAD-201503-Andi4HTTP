<?php

function andi_html_title_tag() {
	global $config;
	return '<title>' . $config['title-callback']() . '</title>';
}

function andi_html_header() {
	global $local_path;
	// Global header.
	require andi_build_dir_path(ANDI_GLOBAL_DIR, 'header.php');
	// Local header.
	$local_header_path = andi_build_dir_path($local_path, 'header.html');
	if (is_file($local_header_path)) {
		echo file_get_contents($local_header_path);
	}
}

function andi_html_footer() {
	global $local_path;
	// Local footer.
	$local_footer_path = andi_build_dir_path($local_path, 'footer.html');
	if (is_file($local_footer_path)) {
		echo file_get_contents($local_footer_path);
	}
	// Global footer.
	require andi_build_dir_path(ANDI_GLOBAL_DIR, 'footer.php');
}

function andi_html_main_table($table_class='') {
	global $config, $url_path_parts, $local_path;
	echo '<table class="', $table_class, '">';

	$finfo = null;
	if ($config['show-mimetype']) {
		$finfo = finfo_open(FILEINFO_MIME_TYPE | FILEINFO_PRESERVE_ATIME);
	}

	echo '<thead>';
	echo '<tr>';
	echo '<th>Name</th>';
	if ($config['show-mimetype'] && $finfo) {
		echo '<th>MIME Type</th>';
	}
	if ($config['show-mtime']) {
		echo '<th>Last Modified</th>';
	}
	if ($config['show-size']) {
		echo '<th>Size</th>';
	}
	echo '</tr>';
	echo '</thead>';

	echo '<tbody>';

	if (count($url_path_parts)) {
		echo '<tr><td><a href="..">../</a></td></tr>';
	}

	andi_list_directory($local_path, function($entry, $path) use ($finfo) {
		global $config;
		echo '<tr>';
		echo '<td><a href="', rawurlencode($entry), '/">', $entry, '/</a></td>';
		if ($config['show-mimetype'] && $finfo) {
			echo '<td>-</td>';
		}
		if ($config['show-mtime']) {
			if ($config['show-mtime-dir']) {
				echo '<td>', date($config['date-format'], filemtime($path)), '</td>';
			} else {
				echo '<td>-</td>';
			}
		}
		if ($config['show-size']) {
			echo '<td>-</td>';
		}
		echo '</tr>';
	}, function($entry, $path) use ($finfo) {
		global $config;
		echo '<tr>';
		echo '<td><a href="', rawurlencode($entry), '">', $entry, '</a></td>';
		if ($config['show-mimetype'] && $finfo) {
			echo '<td>', finfo_file($finfo, $path), '</td>';
		}
		if ($config['show-mtime']) {
			echo '<td>', date($config['date-format'], filemtime($path)), '</td>';
		}
		if ($config['show-size']) {
			echo '<td>', andi_format_size(filesize($path)), '</td>';
		}
		echo '</tr>';
	}, $config['exclude-entries']);

	if ($finfo) {
		finfo_close($finfo);
	}

	echo '</tbody>';
	echo '</table>';
}

?>
