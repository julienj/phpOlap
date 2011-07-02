<?php

require_once __DIR__.'/vendor/Symfony/Component/ClassLoader/UniversalClassLoader.php';

use Symfony\Component\ClassLoader\UniversalClassLoader;

$loader = new UniversalClassLoader();
$loader->registerNamespaces(array(
	'phpOlap\\Tests' => __DIR__.'/tests',
	'phpOlap' => __DIR__.'/src',
	'Symfony' => __DIR__.'/vendor',
));
$loader->register();