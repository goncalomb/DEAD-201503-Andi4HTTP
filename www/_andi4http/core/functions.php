<?php

function andi_build_dir_path() {
	return implode(DIRECTORY_SEPARATOR, array_map(function($value) {
		return (is_array($value) ? call_user_func_array('andi_build_dir_path', $value) : $value);
	}, func_get_args()));
}

function andi_write_htaccess($path, $content, $override=false) {
	if ($override || !is_file($path)) {
		file_put_contents($path, ltrim($content));
	}
}

function andi_require_if_exists($path) {
	if (is_file($path)) {
		return require $path;
	}
}

function andi_format_size($size) {
	if ($size >= 1073741824) {
		return floor($size/10737418.24)/100 . ' GB';
	} else if ($size >= 1048576) {
		return floor($size/10485.76)/100 . ' MB';
	} else if ($size >= 1024) {
		return floor($size/10.24)/100 . ' KB';
	} else if ($size == 1) {
		return $size . ' byte';
	}
	return $size . ' bytes';
}

function andi_list_directory($local_path, $dir_callback, $file_callback, $exclude_entries=array()) {
	$handle = opendir($local_path);
	if ($handle) {
		$folders = array();
		$files = array();

		while (($entry = readdir($handle)) !== false) {
			if ($entry != '.' && $entry != '..' && $entry[0] != '.' && !in_array($entry, $exclude_entries)) {
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
