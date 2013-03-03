<?php

abstract class PHPUnit_Framework_ExtensionTestCase extends PHPUnit_Framework_TestCase
{
    abstract function getFunctions();

    public function getExtensionName() 
    {
    }

    public function testExtensionLoad() 
    {
        if( $name = $this->getExtensionName() ) {
            $this->assertTrue( extension_loaded($name) );
        }
    }

    public function testFunctions() 
    {
        $functions = $this->getFunctions();
        foreach( $functions as $function) {
            $this->assertTrue( function_exists($function));
        }
    }

    public function setUp()
    {
        if( $name = $this->getExtensionName() ) {
            if( extension_loaded($name) ) {
                // we can make tests


            } else {
                // skip if we are already in the child process
                if( isset($_ENV['CHILD']) ) {
                    return $this->markTestIncomplete("Can not load extension");
                }
                // launch process to re-run this phpunit test case

            }
        }
    }
}

