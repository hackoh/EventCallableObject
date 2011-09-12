<?php
class Foo extends EventCallableObject {
	public function __construct() {
		$this->addEventListner('bar', EventCallableObject::START, array($this, 'listner'));
		$this->addEventListner('bar', EventCallableObject::END, array($this, 'listner'));
		$this->addEventListner('piyo', EventCallableObject::START, array($this, 'listner'));
	}
	public function listner($method, $label, $arguments = array()) {
		echo sprintf("method:%s label:%s arguments:%s\n", $method, $label, var_export($arguments, true));
	}
	public function bar() {
		return 'bar';
	}
	public function piyo() {
		return 'piyo';
	}
}