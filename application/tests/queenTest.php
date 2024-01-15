<?php

require_once './src/util.php';
require_once './src/rules/moveHelper.php';
require_once './src/insects/queen.php';

class queenTest extends PHPUnit\Framework\TestCase
{
    public function testGetPossibleMoves()
    {
        $board = ['0,0' => [['0', 'A']], '0,1' => [['1', 'A']], '1,-1' => [['0', 'Q']], '-1,2' => [['1', 'A']]];

        $actualPossibleMovements = ['1,0', '0,-1'];

        $queen = new Queen();

        $projectedPossibleMovements = $queen->getPossibleMoves($board, '1,-1');

        $this->assertEquals(count($actualPossibleMovements), count($projectedPossibleMovements));

        foreach ($projectedPossibleMovements as $place) {
            $this->assertContains($place, $actualPossibleMovements);
        }
    }

    public function testGetPossibleMoves2()
    {
        $board = ['0,0' => [['0', 'A']], '1,0' => [['1', 'A']]];

        $actualPossibleMovement = ['0,1'];

        $queen = new Queen();

        $projectedPossibleMovements = $queen->getPossibleMoves($board, '0,0');

        foreach ($projectedPossibleMovements as $place) {
            $this->assertContains($place, $actualPossibleMovement);
            break;
        }
    }

    public function testGetPossibleMovesBeetleBlocked()
    {
        $board = ['0,0' => [['0', 'A'], ['1', 'B']], '0,1' => [['1', 'A']], '1,-1' => [['0', 'Q']], '-1,2' => [['1', 'A']]];

        $actualPossibleMovements = [];

        $queen = new Queen();

        $projectedPossibleMovements = $queen->getPossibleMoves($board, '0,0');

        $this->assertEquals(count($actualPossibleMovements), count($projectedPossibleMovements));

        foreach ($projectedPossibleMovements as $place) {
            $this->assertContains($place, $actualPossibleMovements);
        }
    }

    public function testGetPossibleMovesSplitHive()
    {
        $board = ['0,0' => [['0', 'Q']], '0,1' => [['1', 'Q']], '1,-1' => [['0', 'A']]];

        $actualPossibleMovements = [];

        $queen = new Queen();

        $projectedPossibleMovements = $queen->getPossibleMoves($board, '0,0');

        $this->assertEquals(count($actualPossibleMovements), count($projectedPossibleMovements));
    }
}