<?php

namespace LetoChat;

use Exception;
use PHPUnit\Framework\TestCase;

class WidgetTest extends TestCase
{
	
	/**
     * @covers LetoChat\Widget::getInfos
     */
    public function testGetInfos()
    {
		$list = [
			'id' 	=> 123,
			'name' 	=> 'Ion Popescu'
		];
		
        $widget = new Widget('channel_id');
		$widget->infoValues($list);
		
        $this->assertEquals($list, $widget->getInfos());
    }

    /**
     * expectedException
     */
    public function testGetInfosException()
    {
        $this->expectException(Exception::class);

        $widget = new Widget('channel_id');
        $widget->infoValues('name', 'Ion Popescu');
    }

    /**
     * expectedException
     */
    public function testInvalidInfo()
    {
        $this->expectException(Exception::class);

        $widget = new Widget('channel_id');
        $widget->infoValues('something', 'Ion Popescu');
    }
	
}