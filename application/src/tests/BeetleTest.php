<?php

namespace tests;

use managers\GameManager;
use mock\MockDatabaseManager;
use PHPUnit\Framework\TestCase;
use insects\Beetle;
use insects\Queen;

class BeetleTest extends TestCase
{
    private GameManager $gameManager;

    protected function setUp(): void
    {
        $this->gameManager = new GameManager(new MockDatabaseManager());

        $this->gameManager->restart();
    }

    public function testGetPossibleMoves()
    {
        $this->gameManager->play('A', '0,0');
        $this->gameManager->play('A', '0,1');
        $this->gameManager->play('B', '1,-1');
        $this->gameManager->play('A', '-1,2');

        $actualPossibleMovements = ['1,0', '0,-1'];

        $beetle = new Beetle();

        $projectedPossibleMovements = $beetle->getPossibleMoves(GameManager::getBoard(), '1,-1');

        $this->assertEquals(count($actualPossibleMovements), count($projectedPossibleMovements));

        foreach ($projectedPossibleMovements as $place) {
            $this->assertContains($place, $actualPossibleMovements);
        }
    }

    public function testIsBeetleBlocked()
    {
        $this->gameManager->play('Q', '0,0');
        $this->gameManager->play('Q', '0,1');
        $this->gameManager->play('B', '1,-1');
        $this->gameManager->play('A', '-1,2');
        $this->gameManager->move('1,-1', '1,0');
        $this->gameManager->move('-1,2', '-1,1');
        $this->gameManager->move('1,0', '0,1');

        $actualPossibleMovements = [];

        $queen = new Queen();

        $projectedPossibleMovements = $queen->getPossibleMoves(GameManager::getBoard(), '0,1');

        $this->assertEquals(count($actualPossibleMovements), count($projectedPossibleMovements));

        foreach ($projectedPossibleMovements as $place) {
            $this->assertContains($place, $actualPossibleMovements);
        }
    }

    public function testCantMoveToOwnPosition()
    {
        $this->gameManager->play('Q', '0,0');
        $this->gameManager->play('Q', '1,0');
        $this->gameManager->play('B', '-1,0');
        $this->gameManager->play('A', '2,0');
        $this->gameManager->move('-1,0', '-1,0');

        $this->assertEquals(0, GameManager::getPlayer());
        $this->assertArrayHasKey('-1,0', GameManager::getBoard());
        $this->assertEquals('B', GameManager::getBoard()['-1,0'][0][1]);
        $this->assertEquals(0, GameManager::getBoard()['-1,0'][0][0]);
    }

    public function testCanMoveWhenOnTop()
    {
        $this->gameManager->play('Q', '0,0');
        $this->gameManager->play('Q', '1,0');
        $this->gameManager->play('B', '-1,0');
        $this->gameManager->play('A', '2,0');
        $this->gameManager->move('-1,0', '0,-1');
        $this->gameManager->play('A', '3,0');
        $this->gameManager->move('0,-1','1,-1');
        $this->gameManager->play('G', '4,0');
        $this->gameManager->move('1,-1','1,0');
        $this->gameManager->play('G', '5,0');
        $this->gameManager->move('1,0','0,1');

        $pieceOnBoard = count(GameManager::getBoard());

        $this->assertEquals(7, $pieceOnBoard);
        $this->assertEquals(1, GameManager::getPlayer());
        $this->assertArrayHasKey('0,1', GameManager::getBoard());
        $this->assertEquals('B', GameManager::getBoard()['0,1'][0][1]);
        $this->assertEquals(0, GameManager::getBoard()['0,1'][0][0]);
    }
}