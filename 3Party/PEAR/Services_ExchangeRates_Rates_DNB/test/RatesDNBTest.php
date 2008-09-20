<?php

require_once 'Ilib/ClassLoader.php';

require_once '../src/Services/ExchangeRates/Rates_DNB.php';

class RatesDNBTest extends PHPUnit_Framework_TestCase
{

    function setUp()
    {
    }
    
    function testRetrieve()
    {
        $rate = new Services_ExchangeRates_Rates_DNB;
        $rates = $rate->retrieve(0, '/tmp/');
        
        $this->assertEquals(date('Y-m-d'), $rates['date']);
        $this->assertEquals(1, $rates['rates']['DKK']);
        $this->assertEquals(745, $rates['rates']['EUR'], 1);
    }

}
?>
