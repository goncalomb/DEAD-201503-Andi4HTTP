<?php

final class Andi {

	static $_urlPath;
	static $_urlPathParts;
	static $_localPath;
	static $_config;
	static $_theme;
	static $_themeConfig;

	static function initialize() {
		if (self::$_urlPath !== null) {
			return;
		}

		// Process the URL.
		$i = strpos($_SERVER['REQUEST_URI'], '?');
		self::$_urlPath = ($i !== false ? substr($_SERVER['REQUEST_URI'], 0, $i) : $_SERVER['REQUEST_URI']);
		unset($i);
		self::$_urlPath = preg_replace('/\/{2,}/', '/', self::$_urlPath);
		self::$_urlPathParts = array_map('rawurldecode', explode('/', trim(self::$_urlPath, '/')));
		if (count(self::$_urlPathParts) == 1 && self::$_urlPathParts[0] == '') {
			self::$_urlPathParts = array();
		}
		self::$_localPath = andi_build_dir_path(ANDI_ROOT_DIR, self::$_urlPathParts);
		// TODO: This only works if the root of the domain is the same as the root for the listing.

		// Load config.php file.
		$config_closure = function() {
			$config = array();
			require ANDI_CONFIG_FILE;
			return $config;
		};
		self::$_config = $config_closure();
		unset($config_closure);

		// Theme and theme config.
		self::$_theme = (
			isset(self::$_config['theme']) ?
			strtolower(self::$_config['theme']) :
			'plain'
		);
		self::$_themeConfig = (
			isset(self::$_config['theme-config'][self::$_theme]) ?
			self::$_config['theme-config'][self::$_theme] :
			array()
		);

		unset(self::$_config['theme']);
		unset(self::$_config['theme-config']);
	}

	static function config($key=null) {
		if ($key) {
			$result = self::$_config;
			foreach (func_get_args() as $key) {
				if (!isset($result[$key])) {
					return null;
				}
				$result = $result[$key];
			}
			return $result;
		}
		return null;
	}

	static function theme() {
		return self::$_theme;
	}

	static function themeConfig($key) {
		return (isset(self::$_themeConfig[$key]) ? self::$_themeConfig[$key] : null);
	}

	static function urlPath($as_array=false) {
		if ($as_array) {
			return self::$_urlPathParts;
		} else {
			return self::$_urlPath;
		}
	}

	static function localPath() {
		return self::$_localPath;
	}

}

?>
