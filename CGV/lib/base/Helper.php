<?php

class Helper {
	
	public static function hash($string) {
		return sha1($string);
	}
	
	public static function verifyHash($password, $hash) {
		return $hash == Helper::hash($password);
	}
	
	public static function redirect($url) {
		header('Location: ' . BASE_URL . $url);
	}
	
}