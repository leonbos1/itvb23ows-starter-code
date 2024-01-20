<?php

namespace tests;

use PHPUnit\Framework\TestCase;
use managers\GameManager;
use mock\MockDatabaseManager;

class WinTest extends TestCase
{
    private GameManager $gameManager;

    protected function setUp(): void
    {
        $this->gameManager = new GameManager(new MockDatabaseManager());

        $this->gameManager->restart();
    }

    public function testWhiteWin()
    {
        $this->gameManager->play('Q', '0,0');
        $this->gameManager->play('Q', '0,1');
        $this->gameManager->play('A', '0,-1');
        $this->gameManager->play('A', '1,1');
        $this->gameManager->play('G', '-1,0');
        $this->gameManager->play('A', '-1,2');
        $this->gameManager->play('G', '1,-1');
        $this->gameManager->move('1,1', '1,0');
        $this->gameManager->play('S', '-1,-1');
        $this->gameManager->move('-1,2', '-1,1');

        $piecesOnBoard = count(GameManager::getBoard());

        $this->assertEquals(8, $piecesOnBoard);
        $this->assertArrayHasKey('1,0', GameManager::getBoard());
        $this->assertArrayHasKey('-1,-1', GameManager::getBoard());

        $this->assertEquals('White player won', GameManager::getError());
    }

    public function testBlackWin()
    {
        GameManager::setPlayer(1);
        $this->gameManager->play('Q', '0,0');
        $this->gameManager->play('Q', '0,1');
        $this->gameManager->play('A', '0,-1');
        $this->gameManager->play('A', '1,1');
        $this->gameManager->play('G', '-1,0');
        $this->gameManager->play('A', '-1,2');
        $this->gameManager->play('G', '1,-1');
        $this->gameManager->move('1,1', '1,0');
        $this->gameManager->play('S', '-1,-1');
        $this->gameManager->move('-1,2', '-1,1');

        $piecesOnBoard = count(GameManager::getBoard());

        $this->assertEquals(8, $piecesOnBoard);
        $this->assertArrayHasKey('1,0', GameManager::getBoard());
        $this->assertArrayHasKey('-1,-1', GameManager::getBoard());

        $this->assertEquals('Black player won', GameManager::getError());
    }

    public function testTie()
    {
        $this->gameManager->play('Q', '0,0');
        $this->gameManager->play('Q', '0,1');
        $this->gameManager->play('S', '0,-1');
        $this->gameManager->play('S', '1,1');
        $this->gameManager->play('S', '1,-1');
        $this->gameManager->play('G', '0,2');
        $this->gameManager->play('A', '-1,0');
        $this->gameManager->play('S', '1,2');
        $this->gameManager->play('A', '-2,1');
        $this->gameManager->move('1,2', '1,0');
        $this->gameManager->play('G', '-1,-1');
        $this->gameManager->play('A', '-1,2');
        $this->gameManager->move('-2,1', '-1,1');

        $piecesOnBoard = count(GameManager::getBoard());

        $this->assertEquals(11, $piecesOnBoard);
        $this->assertEquals('Game is tied', GameManager::getError());
    }
}