<?php

namespace tests;

use managers\GameManager;
use PHPUnit\Framework\TestCase;
use mock\MockDatabaseManager;

class UndoTest extends TestCase
{
    private GameManager $gameManager;

    protected function setUp(): void
    {
        $this->gameManager = new GameManager(new MockDatabaseManager());

        $this->gameManager->restart();
    }

    public function testUndo1()
    {
        $this->gameManager->play('A', '0,0');
        $this->gameManager->play('A', '0,1');
        $this->gameManager->play('B', '1,-1');
        $this->gameManager->play('A', '-1,2');
        $this->gameManager->play('B', '0,-1');
        $this->gameManager->play('A', '-2,2');

        $this->gameManager->undo();

        $piecesOnBoard = count(GameManager::getBoard());

        $this->assertEquals(5, $piecesOnBoard);
        $this->assertArrayNotHasKey('-2,2', GameManager::getBoard());
    }

    public function testUndo2()
    {
        $this->gameManager->play('A', '0,0');
        $this->gameManager->play('A', '0,1');
        $this->gameManager->play('B', '1,-1');
        $this->gameManager->play('A', '-1,2');
        $this->gameManager->play('B', '0,-1');
        $this->gameManager->play('A', '-2,2');

        $this->gameManager->undo();

        $this->assertEquals('B', GameManager::getBoard()[array_key_last(GameManager::getBoard())][0][1]);
    }

    public function testUndoMultiple()
    {
        $this->gameManager->play('A', '0,0');
        $this->gameManager->play('B', '0,1');
        $this->gameManager->play('A', '0,-1');

        $this->gameManager->undo();
        $this->gameManager->undo();

        $piecesOnBoard = count(GameManager::getBoard());
        $this->assertEquals(1, $piecesOnBoard);
        $this->assertArrayHasKey('0,0', GameManager::getBoard());
        $this->assertArrayNotHasKey('0,1', GameManager::getBoard());
        $this->assertArrayNotHasKey('0,-1', GameManager::getBoard());
    }

    public function testUndoPlaySameTileSameCoordinate()
    {
        $this->gameManager->play('A', '0,0');
        $this->gameManager->play('B', '0,1');
        $this->gameManager->play('A', '0,-1');
        $this->gameManager->play('B', '0,2');
        $this->gameManager->play('Q', '1,-2');

        $this->gameManager->undo();
        $this->gameManager->play('Q', '1,-2');

        error_log(print_r(GameManager::getBoard(), true));

        $piecesOnBoard = count(GameManager::getBoard());

        $this->assertEquals(5, $piecesOnBoard);

        $this->assertArrayHasKey('0,0', GameManager::getBoard());
        $this->assertArrayHasKey('0,1', GameManager::getBoard());
        $this->assertArrayHasKey('0,-1', GameManager::getBoard());
        $this->assertArrayHasKey('0,2', GameManager::getBoard());
        $this->assertArrayHasKey('1,-2', GameManager::getBoard());
    }

    public function testUndoMultiple2()
    {
        $this->gameManager->play('A', '0,0');
        $this->gameManager->play('B', '0,1');
        $this->gameManager->play('A', '0,-1');
        $this->gameManager->play('B', '0,2');
        $this->gameManager->play('Q', '1,-2');

        $this->gameManager->undo();
        $this->gameManager->undo();

        error_log(print_r(GameManager::getBoard(), true));

        $piecesOnBoard = count(GameManager::getBoard());

        $this->assertEquals(3, $piecesOnBoard);

        $this->assertArrayHasKey('0,0', GameManager::getBoard());
        $this->assertArrayHasKey('0,1', GameManager::getBoard());
        $this->assertArrayHasKey('0,-1', GameManager::getBoard());
    }

    public function testUndoSpamClick()
    {
        $this->gameManager->play('A', '0,0');
        $this->gameManager->play('B', '0,1');
        $this->gameManager->play('A', '0,-1');

        $this->gameManager->undo();
        $this->gameManager->undo();
        $this->gameManager->undo();
        $this->gameManager->undo();
        $this->gameManager->undo();
        $this->gameManager->undo();

        error_log(print_r(GameManager::getBoard(), true));

        $piecesOnBoard = count(GameManager::getBoard());

        $this->assertEquals(0, $piecesOnBoard);
        $this->assertArrayNotHasKey('0,0', GameManager::getBoard());
        $this->assertArrayNotHasKey('0,1', GameManager::getBoard());
        $this->assertArrayNotHasKey('0,-1', GameManager::getBoard());
    }
}