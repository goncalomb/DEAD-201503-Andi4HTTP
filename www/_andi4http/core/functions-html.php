<?php

function andi_html_header_global() {
	if (Andi::config('header-global')) {
		andi_require_if_exists(ANDI_DIR . DIRECTORY_SEPARATOR . 'header.php');
	}
}

function andi_html_header_local() {
	if (Andi::config('header-local')) {
		andi_require_if_exists(Andi::localPath() . DIRECTORY_SEPARATOR . 'header.html');
	}
}

function andi_html_footer_global() {
	if (Andi::config('footer-global')) {
		andi_require_if_exists(ANDI_DIR . DIRECTORY_SEPARATOR . 'footer.php');
	}
}

function andi_html_footer_local() {
	if (Andi::config('footer-local')) {
		andi_require_if_exists(Andi::localPath() . DIRECTORY_SEPARATOR . 'footer.html');
	}
}

function andi_html_error() {
	andi_require_if_exists(ANDI_DIR . DIRECTORY_SEPARATOR . 'error.php');
}

function andi_html_breadcrumbs() {
	$url_path_parts = Andi::urlPath(true);
	$c = $i = count($url_path_parts);
	if (!Andi::config('breadcrumbs') || (!Andi::config('breadcrumbs-on-root') && $c == 0)) {
		return;
	}
	echo '<ol class="breadcrumb">';
	if ($c > 0) {
		echo '<li><a href="', str_repeat('../', $i--), '">', $_SERVER['HTTP_HOST'], '</a></li>';
	}
	foreach ($url_path_parts as $part) {
		if ($i == 0) break;
		echo '<li><a href="', str_repeat('../', $i--), '">', $part, '</a></li>';
	}
	if ($c > 0) {
		echo '<li class="active">', $url_path_parts[$c - 1], '</li>';
	} else {
		echo '<li class="active">', $_SERVER['HTTP_HOST'], '</li>';
	}
	echo '</ol>';
}

function andi_html_main_table() {
	echo '<table class="table">';

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

	if (Andi::config('parent-link') && count(Andi::urlPath(true))) {
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

function andi_html_all($no_breadcrumbs=false) {
	if (ANDI_ERROR_CODE != 200) {
		andi_html_error();
		return;
	}
	echo '<header class="global">';
	andi_html_header_global();
	echo '</header>';
	if (!$no_breadcrumbs) {
		andi_html_breadcrumbs();
	}
	echo '<header class="local">';
	andi_html_header_local();
	echo '</header>';
	echo '<main>';
	andi_html_main_table();
	echo '</main>';
	echo '<footer class="local">';
	andi_html_footer_local();
	echo '</footer>';
	echo '<footer class="global">';
	andi_html_footer_global();
	echo '</footer>';
}

?>
