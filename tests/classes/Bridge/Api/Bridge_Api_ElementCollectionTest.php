<?php

require_once __DIR__ . '/../Bridge_datas.inc';

class Bridge_Api_ElementCollectionTest extends PHPUnit_Framework_TestCase
{

    public function testAdd_element()
    {
        $elements = array();
        $collection = new Bridge_Api_ElementCollection();
        $i = 0;
        while ($i < 5) {
            $elements[] = $element = $this->getMock("Bridge_Api_ElementInterface");
            $collection->add_element(new $element);
            $i ++;
        }
        $this->assertEquals(5, sizeof($collection->get_elements()));
    }
}
