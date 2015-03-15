<?php

final class Andi {

	static $_config;

	static function initialize() {
		$config_closure = function() {
			$config = array();
			require ANDI_CONFIG_FILE;
			return $config;
		};
		self::$_config = $config_closure();
		unset($config_closure);
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

}

?>
