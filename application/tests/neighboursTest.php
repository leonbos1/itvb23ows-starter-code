<?php

require_once './src/util.php';
require_once './src/rules/moveHelper.php';

class neighboursTest extends PHPUnit\Framework\TestCase
{
    public function testNeighboursSimple()
    {
        $neighbours = getNeighbours('0,0');

        $actualNeighbours = ['0,1', '0,-1', '1,0', '-1,0', '-1,1', '1,-1'];

        $this->assertEquals(count($actualNeighbours), count($neighbours));

        foreach ($neighbours as $neighbour) {
            $this->assertContains($neighbour, $actualNeighbours);
        }
    }


    public function testNeighbours()
    {
        $actualNeighbours = ['1,1', '0,2', '-1,2', '-1,1', '0,0', '1,0'];

        $projectedNeighbours = getNeighbours('0,1');

        $this->assertEquals(count($actualNeighbours), count($projectedNeighbours));

        foreach ($projectedNeighbours as $place) {
            $this->assertContains($place, $actualNeighbours);
        }
    }

    public function testNeighboursSameColorSimple()
    {
        $board = ['0,0' => [['0', 'Q']], '0,1' => [['1', 'A']], '1,0' => [['0', 'A']]];

        $neighbours = getNeighboursSameColor($board, '0', '0,0');

        $actualNeighbours = ['1,0'];

        $this->assertNotEmpty($neighbours);
        $this->assertEquals(count($actualNeighbours), count($neighbours));

        foreach ($neighbours as $neighbour) {
            $this->assertContains($neighbour, $actualNeighbours);
        }
    }

    public function testIsNeighbour()
    {
        $this->assertTrue(isNeighbour('0,0', '0,1'));
        $this->assertTrue(isNeighbour('0,0', '0,-1'));
        $this->assertTrue(isNeighbour('0,0', '1,0'));
        $this->assertTrue(isNeighbour('0,0', '-1,0'));
        $this->assertTrue(isNeighbour('0,0', '-1,1'));
        $this->assertTrue(isNeighbour('0,0', '1,-1'));

        $this->assertFalse(isNeighbour('0,0', '1,1'));
        $this->assertFalse(isNeighbour('0,0', '2,0'));
        $this->assertFalse(isNeighbour('0,0', '0,2'));
        $this->assertFalse(isNeighbour('0,0', '2,2'));
        $this->assertFalse(isNeighbour('0,0', '-2,-2'));
        $this->assertFalse(isNeighbour('2,-1', '0,1'));
    }
}