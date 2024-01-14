<?php

require_once './src/util.php';
require_once './src/rules/moveHelper.php';

class hasNeighbourTest extends PHPUnit\Framework\TestCase
{
    public function testHasNeighbour()
    {
        $board = ['0,0' => [['0', 'A']], '0,1' => [['1', 'A']], '1,-1' => [['0', 'Q']], '-1,2' => [['1', 'A']]];

        $hasNeighbour = hasNeighbour('0,-1', $board);

        $this->assertTrue($hasNeighbour);
    }
}