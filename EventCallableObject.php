<?php
class EventCallableObject {
	const START = 1;
	const END = 2;
	private static $_autoloadDirectories = array();
	private static $_events = array();
	private $_hash;
	public function __construct() {
		$this->_hash = self::_getRandomString(15);
	}
	public function addEventListner($method, $label = self::START, $handler) {
		self::$_events[$this->_hash][$method][$label] = $handler;
	}
	public function __call($method, $arguments) {
		$this->triggerEvent(self::START, array($method, self::START, $arguments));
		$returnValue = call_user_func_array(array($this, '__'.$method.'__'), $arguments);
		$this->triggerEvent(self::END, array($method, self::END, $arguments));
		return $returnValue;
	}
	public static function autoload($className) {
		foreach (self::$_autoloadDirectories as $directory) {
			$filepath = $directory.'/'.$className.'.php';
			if (file_exists($filepath)) {
				$content = file_get_contents($filepath);
				$content = preg_replace(array('/(function\s+)([a-zA-Z0-9]+)/', '/\<\?php|\?\>/'), array('$1__$2__$3', ''), $content);
				eval($content);
				return;
			}
		}
	}
	public static function pushDirectory($directory) {
		self::$_autoloadDirectories[] = $directory;
	}
	protected function triggerEvent($label, $arguments) {
		$method = $arguments[0];
		if (isset(self::$_events[$this->_hash][$method][$label]))
			call_user_func_array(self::$_events[$this->_hash][$method][$label], $arguments);
	}
	private static function _getRandomString($length = 8){
		$list = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
		mt_srand();
		$result = '';
		for ($i = 0; $i < $length; $i++) $result .= $list{mt_rand(0, strlen($list) - 1)};
		return $result;
	}
}
spl_autoload_register(array('EventCallableObject', 'autoload'), false, true);