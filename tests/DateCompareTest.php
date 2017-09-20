<?php

use PHPUnit\Framework\TestCase;
use NikitaKurdin\DateCompare\DateCompare;

class DateCompareTest extends TestCase
{
	private $DateCompare;
 
    protected function setUp()
    {
        $this->DateCompare = new DateCompare();
    }

	/**
	 * [testConvertTime Проверяем конвертацию времени]
	 */
    public function testConvertTime()
    {
    	$test = array(
    					"01" 		=> '3600',
    					"01:20" 	=> '4800',
    					"01:20:17" 	=> '4817',
    				);

    	foreach ($test as $check => $match)
        {
        	$result = $this->DateCompare->ConvertTime($check);
	        $this->assertEquals($match, $result, $check);
	    }
    }

    /**
     * [testConvertDate Проверяем конвертацию даты]
     */
    public function testConvertDate()
    {
    	$test = array(
    					"21.07.2017" 	=> '1500584400',
    					"07.2017" 		=> '1498856400',
    					"2017" 			=> '1483218000',
    				);

    	foreach ($test as $check => $match)
        {
        	$result = $this->DateCompare->ConvertDate($check);
	        $this->assertEquals($match, $result, $check);
	    }
    }

    /**
     * [testConvertFormate Проверяем конвертацию времени и даты]
     */
    public function testConvertFormate()
    {
    	$test = array(
    					"01:00:05 21.07.2017" 	=> '1500588005',
    					"01:05 21.07.2017" 		=> '1500588300',
    					"01: 21.07.2017" 		=> '1500588000',
    					"21.07.2017" 			=> '1500584400',
    					"07.2017"				=> '1498856400',
    					"2017" 					=> '1483218000',
    					"01:" 					=> '3600',
    					"01:05" 				=> '3900',
    					"01:05:17"				=> '3917',
    				);

        foreach ($test as $check => $match)
        {
	        $result = $this->DateCompare->ConvertFormate($check);
	        $this->assertEquals($match, $result, $check);
	    }
    }
}
