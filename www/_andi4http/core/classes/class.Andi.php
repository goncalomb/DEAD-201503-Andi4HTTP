<?php

final class Andi {

	static $_urlBase;
	static $_urlBaseParts;
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

		// URL parsing is tricky. Rewrites and aliases break everything.
		// We probably need a 'base-path' config to avoid problems with more
		// complicated setups.

		// TODO: More tests using Apache mod_alias, sub-domains etc. this code
		//       will probably fail in some of these cases.

		// For now it works when installed on the server root or on a
		// sub-directory without fancy rewrites/aliases.

		// Try to find the base path.
		// TODO: Try to detect special cases and ask the admin to set a config.
		self::$_urlBase = substr(ANDI_ROOT_DIR, strlen(rtrim($_SERVER['DOCUMENT_ROOT'], '\\/')));
		self::$_urlBase = str_replace(DIRECTORY_SEPARATOR, '/', self::$_urlBase);
		self::$_urlBaseParts = explode(DIRECTORY_SEPARATOR, trim(self::$_urlBase, '/'));
		if (count(self::$_urlBaseParts) == 1 && self::$_urlBaseParts[0] == '') {
			self::$_urlBaseParts = array();
		}

		// Process the URL.
		$i = strpos($_SERVER['REQUEST_URI'], '?');
		self::$_urlPath = ($i !== false ? substr($_SERVER['REQUEST_URI'], 0, $i) : $_SERVER['REQUEST_URI']);
		unset($i);
		self::$_urlPath = preg_replace('/\/{2,}/', '/', self::$_urlPath);
		// TODO: Detect if urlPath doesn't start with urlBase.
		self::$_urlPath = substr(self::$_urlPath, strlen(self::$_urlBase));
		self::$_urlPathParts = array_map('rawurldecode', explode('/', trim(self::$_urlPath, '/')));
		if (count(self::$_urlPathParts) == 1 && self::$_urlPathParts[0] == '') {
			self::$_urlPathParts = array();
		}

		// Build the local path (file-system path).
		self::$_localPath = andi_build_dir_path(ANDI_ROOT_DIR, self::$_urlPathParts);

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

	static function urlBase($as_array=false) {
		return ($as_array ? self::$_urlBaseParts : self::$_urlBase);
	}

	static function urlPath($as_array=false) {
		return ($as_array ? self::$_urlPathParts : self::$_urlPath);
	}

	static function localPath() {
		return self::$_localPath;
	}

}

?>
