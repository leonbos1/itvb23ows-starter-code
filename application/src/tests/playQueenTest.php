<?php

namespace tests;

use helpers\RuleHelper;
use managers\GameManager;
use mock\MockDatabaseManager;
use PHPUnit\Framework\TestCase;

class PlayQueenTest extends TestCase
{
    private GameManager $gameManager;

    protected function setUp(): void
    {
        $this->gameManager = new GameManager(new MockDatabaseManager());

        $this->gameManager->restart();
    }

    public function testPlayerMustPlayQueen()
    {
        $this->gameManager->play('A', '0,0');
        $this->gameManager->play('A', '0,1');
        $this->gameManager->play('B', '1,-1');
        $this->gameManager->play('A', '-1,2');
        $this->gameManager->play('B', '0,-1');
        $this->gameManager->play('A', '-2,2');

        $board = $this->gameManager->getBoard();
        $piece = 'A';
        $hand = $this->gameManager->getHand(0);

        $this->assertTrue(RuleHelper::playerMustPlayQueen($piece, $board, $hand));
    }

    public function testPlayerNotMustPlayQueen()
    {
        $this->gameManager->play('A', '0,0');
        $this->gameManager->play('A', '0,1');
        $this->gameManager->play('B', '1,-1');
        $this->gameManager->play('A', '-1,2');
        $this->gameManager->play('B', '0,-1');
        $this->gameManager->play('A', '-2,2');

        $board = $this->gameManager->getBoard();
        $piece = 'Q';
        $hand = $this->gameManager->getHand(0);

        $this->assertFalse(RuleHelper::playerMustPlayQueen($piece, $board, $hand));
    }

    public function testPlayerNotMustPlayQueen2()
    {
        $this->gameManager->play('A', '0,0');
        $this->gameManager->play('A', '0,1');
        $this->gameManager->play('B', '1,-1');
        $this->gameManager->play('A', '-1,2');

        $board = $this->gameManager->getBoard();
        $piece = 'A';
        $hand = $this->gameManager->getHand(0);

        $this->assertFalse(RuleHelper::playerMustPlayQueen($piece, $board, $hand));
    }
}