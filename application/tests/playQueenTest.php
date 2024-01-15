<?php

require_once './src/insects/insect.php';
require_once './src/insects/queen.php';
require_once './src/util.php';
require_once './src/rules/moveHelper.php';


class PlayQueenTest extends PHPUnit\Framework\TestCase
{
    public function testPlayerMustPlayQueen()
    {
        $board = ['0,0' => [['0', 'A']], '0,1' => [['1', 'A']], '1,-1' => [['0', 'B']], '-1,2' => [['1', 'A']], '0,-1' => [['0', 'B']], '-2,2' => [['1', 'Q']]];

        $hand = ['A' => 2, 'B' => 0, 'Q' => 1, 'S' => 2, 'G' => 3];

        $piece = 'A';

        $this->assertTrue(playerMustPlayQueen($piece, $board, $hand));
    }

    public function testPlayerNotMustPlayQueen()
    {
        $board = ['0,0' => [['0', 'A']], '0,1' => [['1', 'A']], '1,-1' => [['0', 'B']], '-1,2' => [['1', 'A']], '0,-1' => [['0', 'B']], '-2,2' => [['1', 'Q']]];

        $hand = ['A' => 2, 'B' => 0, 'Q' => 1, 'S' => 2, 'G' => 3];

        $piece = 'Q';

        $this->assertFalse(playerMustPlayQueen($piece, $board, $hand));
    }

    public function testPlayerNotMustPlayQueen2()
    {
        $board = ['0,0' => [['0', 'A']], '0,1' => [['1', 'A']], '1,-1' => [['0', 'B']], '-1,2' => [['1', 'A']]];

        $hand = ['A' => 2, 'B' => 1, 'Q' => 1, 'S' => 2, 'G' => 3];

        $piece = 'A';

        $this->assertFalse(playerMustPlayQueen($piece, $board, $hand));
    }
}