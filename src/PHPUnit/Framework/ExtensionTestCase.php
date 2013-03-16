<?php

abstract class PHPUnit_Framework_ExtensionTestCase extends PHPUnit_Framework_TestCase
{
    public function getFunctions() { return array(); }
    public function getClasses() { return array(); }

    public function getExtensionName() {
    }


    public function testClasses()
    {
        $cs = $this->getClasses();
        foreach( $cs as $c) {
            $this->assertTrue( class_exists($c), "Check class existence for $c");
        }

    }

    /**
     * Make sure all defined function exists
     */
    public function testFunctions() 
    {
        $functions = $this->getFunctions();
        foreach( $functions as $f) {
            $this->assertTrue( function_exists($f), "Check function existence for $f");
        }
    }
}

