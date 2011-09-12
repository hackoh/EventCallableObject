<?php
require 'EventCallableObject.php';
EventCallableObject::pushDirectory('.');
$foo = new Foo;
echo $foo->bar(1, 2, 3);