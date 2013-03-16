<?php
require 'PHPUnit/TestMore.php';
require 'Universal/ClassLoader/BasePathClassLoader.php';
require 'src/EXTUnit.php';
require 'src/EXTUnit/TestRunner.php';
define('BASEDIR',dirname(dirname(__FILE__)));
$classLoader = new \Universal\ClassLoader\BasePathClassLoader(array( 
    BASEDIR . '/src',
    BASEDIR . '/vendor/pear',
));
$classLoader->useIncludePath(false);
$classLoader->register();
