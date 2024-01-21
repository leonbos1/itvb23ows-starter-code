<?php

namespace tests;

use helpers\WinHelper;
use managers\GameManager;
use managers\AiManager;
use mock\MockDatabaseManager;
use PHPUnit\Framework\TestCase;

class AiTest extends TestCase
{
    private GameManager $gameManager;
    private AiManager $aiManager;

    protected function setUp(): void
    {
        $this->gameManager = new GameManager(new MockDatabaseManager());
        $this->aiManager = new AiManager();
    }

    public function testValidAiPlay()
    {
        $this->gameManager->play('Q', '0,0');
        $this->gameManager->play('Q', '0,1');
        $this->gameManager->play('A', '0,-1');
        $this->gameManager->getAiMove();

        $piecesOnBoard = count(GameManager::getBoard());

        $this->assertEquals(4, $piecesOnBoard);
    }

    public function testValidAiMove()
    {
        $this->gameManager->play('Q', '0,0');
        $this->gameManager->play('Q', '0,1');
        $this->gameManager->play('A', '0,-1');
        $this->gameManager->play('A', '-1,2');
        $this->gameManager->play('G', '1,-1');
        $this->gameManager->play('G', '-2,3');
        $this->gameManager->play('S', '-1,0');
        $this->gameManager->play('S', '-1,3');
        $this->gameManager->play('S', '2,-2');
        $this->gameManager->play('S', '0,2');
        $this->gameManager->play('G', '-1,-1');
        $this->gameManager->play('G', '0,3');
        $this->gameManager->play('G', '2,-1');
        $this->gameManager->play('G', '1,2');
        $this->gameManager->play('A', '0,-2');
        $this->gameManager->play('A', '-2,2');
        $this->gameManager->play('A', '-2,0');
        $this->gameManager->play('A', '-3,3');
        $this->gameManager->play('B', '1,-2');
        $this->gameManager->play('B', '1,1');
        $this->gameManager->play('B', '0,-3');
        $this->gameManager->play('B', '2,1');
        $this->gameManager->move('0,-3', '1,-3');
        $this->gameManager->getAiMove();

        $piecesOnBoard = count(GameManager::getBoard());

        $this->assertEquals(22, $piecesOnBoard);
    }

    public function testAiWin()
    {
        $this->gameManager->play('Q', '0,0');
        $this->gameManager->play('Q', '0,1');
        $this->gameManager->play('A', '0,-1');
        $this->gameManager->play('A', '-1,2');
        $this->gameManager->play('G', '1,-1');
        $this->gameManager->play('G', '-2,3');
        $this->gameManager->play('S', '-1,0');
        $this->gameManager->play('S', '-1,3');
        $this->gameManager->play('S', '2,-2');
        $this->gameManager->play('S', '0,2');
        $this->gameManager->play('G', '-1,-1');
        $this->gameManager->play('G', '0,3');
        $this->gameManager->play('G', '2,-1');
        $this->gameManager->play('G', '1,2');
        $this->gameManager->play('A', '0,-2');
        $this->gameManager->play('A', '-2,2');
        $this->gameManager->play('A', '-2,0');
        $this->gameManager->play('A', '-3,3');
        $this->gameManager->play('B', '1,-2');
        $this->gameManager->play('B', '1,1');
        $this->gameManager->play('B', '0,-3');
        $this->gameManager->play('B', '2,1');
        $this->gameManager->move('0,-3', '1,-3');
        $this->gameManager->getAiMove();
        $this->gameManager->move('0,-2', '-2,1');
        $this->gameManager->getAiMove();

        $piecesOnBoard = count(GameManager::getBoard());

        $this->assertEquals(true, WinHelper::isGameOver());
        $this->assertEquals('White player won', GameManager::getError());
        $this->assertEquals(22, $piecesOnBoard);
    }
}