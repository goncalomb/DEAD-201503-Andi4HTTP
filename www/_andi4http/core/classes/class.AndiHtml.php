<?php

final class AndiHtml {

	private static $_cssFiles = array();
	private static $_jsFiles = array();
	private static $_headExtra = array();

	public static function start() {
		ob_start();
	}

	public static function addCssFile($href) {
		self::$_cssFiles[] = $href;
	}

	public static function addJsFile($src) {
		self::$_jsFiles[] = $src;
	}

	public static function appendToHead($code) {
		foreach (func_get_args() as $code) {
			self::$_headExtra[] = $code;
		}
	}

	public static function end() {
		$content = ob_get_clean();

		echo "<!DOCTYPE html>\n";
		echo '<html>';
		echo '<head>';
		echo '<meta charset="utf-8">';
		foreach (self::$_cssFiles as $href) {
			echo '<link rel="stylesheet" type="text/css" href="', $href, '">';
		}
		foreach (self::$_jsFiles as $src) {
			echo '<script type="text/javascript" src="', $src, '">';
		}
		echo andi_html_title_tag();
		foreach (self::$_headExtra as $code) {
			echo $code;
		}
		echo '</head>';
		echo '<body>';

		echo $content;

		echo '</body>';
		echo '</html>';

		echo "\n";
		exit();
	}

}

?>
