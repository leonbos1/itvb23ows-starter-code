<?php

require_once './src/insects/insect.php';
require_once './src/insects/queen.php';
require_once './src/insects/grasshopper.php';
require_once './src/insects/beetle.php';
require_once './src/insects/spider.php';
require_once './src/insects/ant.php';


class InsectTest extends PHPUnit\Framework\TestCase
{
    public function testValidInsectType()
    {
        $validTypes = ['A', 'B', 'G', 'Q', 'S'];

        foreach ($validTypes as $type) {
            $insect = getInsectInstance($type);
            $this->assertInstanceOf(Insect::class, $insect);
        }
    }

    public function testInvalidInsectType()
    {
        $invalidTypes = ['d', '1', '!', ''];

        foreach ($invalidTypes as $type) {
            $insect = getInsectInstance($type);
            $this->assertNull($insect);
        }
    }

    public function testLowerCasedInsectType()
    {
        $insect = getInsectInstance('a');
        $this->assertInstanceOf(Insect::class, $insect);
    }
}