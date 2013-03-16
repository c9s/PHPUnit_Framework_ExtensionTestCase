<?php

class EXTUnitTest extends PHPUnit_Framework_TestCase
{
    public function test()
    {
        $extunit = new EXTUnit("tests/config1/extunit.xml");
        ok($extunit);

        $extension = $extunit->getExtensionName();
        ok( $extension );
        is( 'apc', $extension );
    }
}

