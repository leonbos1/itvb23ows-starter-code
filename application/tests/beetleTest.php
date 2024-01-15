<?php

require_once './src/util.php';
require_once './src/rules/moveHelper.php';
require_once './src/insects/beetle.php';

class beetleTest extends PHPUnit\Framework\TestCase
{
    public function testGetPossibleMoves()
    {
        $board = ['0,0' => [['0', 'B']], '0,1' => [['1', 'A']], '1,-1' => [['0', 'Q']], '-1,2' => [['1', 'A']], '1,0' => ['1', 'Q']];

        $actualPossibleMovements = ['-1,1', '0,1', '1,0', '1,-1', '0,-1'];

        $beetle = new Beetle();

        $projectedPossibleMovements = $beetle->getPossibleMoves($board, '0,0');

        $this->assertEquals(count($actualPossibleMovements), count($projectedPossibleMovements));

        foreach ($projectedPossibleMovements as $place) {
            $this->assertContains($place, $actualPossibleMovements);
        }
    }
}