<?php

function andi_build_dir_path() {
	return implode(DIRECTORY_SEPARATOR, array_map(function($value) {
		return (is_array($value) ? call_user_func_array('andi_build_dir_path', $value) : $value);
	}, func_get_args()));
}

function andi_write_text_file($path, $heredoc_content) {
	file_put_contents($path, str_replace("\t", '', $heredoc_content) . "\n");
}

function andi_list_directory($local_path, $dir_callback, $file_callback) {
	$handle = opendir($local_path);
	if ($handle) {
		$folders = array();
		$files = array();

		while (($entry = readdir($handle)) !== false) {
			if ($entry != '.' && $entry != '..' && $entry[0] != '.' && !in_array($entry, array('_andi4http', 'index.php', 'header.html', 'footer.html'))) {
				$path = $local_path . DIRECTORY_SEPARATOR . $entry;
				if (is_dir($path)) {
					$folders[$entry] = $path;
				} else if (is_file($path)) {
					$files[$entry] = $path;
				} else if (is_link($path)) {
					$link_path = readlink($path);
					if (is_dir($link_path)) {
						$folders[$entry] = $path;
					} else if (is_file($link_path)) {
						$files[$entry] = $path;
					}
				}
			}
		}
		closedir($handle);

		ksort($folders);
		ksort($files);

		foreach ($folders as $entry => $path) {
			$dir_callback($entry, $path);
		}
		foreach ($files as $entry => $path) {
			$file_callback($entry, $path);
		}
	}
}

?>
