<?php

namespace tests;

use managers\GameManager;
use mock\MockDatabaseManager;
use PHPUnit\Framework\TestCase;
use insects\Spider;

class SpiderTest extends TestCase
{
    private GameManager $gameManager;

    protected function setUp(): void
    {
        $this->gameManager = new GameManager(new MockDatabaseManager());

        $this->gameManager->restart();
    }

    public function testGetPossibleMoves()
    {
        $this->gameManager->play('Q', '0,0');
        $this->gameManager->play('Q', '0,1');
        $this->gameManager->play('S', '-1,0');
        $this->gameManager->play('A', '0,2');

        $actualPossibleMovements = ['1,0', '-1,3'];

        $spider = new Spider();

        $projectedPossibleMovements = $spider->getPossibleMoves(GameManager::getBoard(), '-1,0');

        $this->assertEquals(count($actualPossibleMovements), count($projectedPossibleMovements));

        foreach ($projectedPossibleMovements as $place) {
            $this->assertContains($place, $actualPossibleMovements);
        }
    }

    public function testGetPossibleMoves2()
    {
        $this->gameManager->play('Q', '0,0');
        $this->gameManager->play('Q', '0,1');
        $this->gameManager->play('S', '-1,0');
        $this->gameManager->play('S', '0,2');
        $this->gameManager->play('A', '0,-1');
        $this->gameManager->play('S', '1,1');

        $actualPossibleMovements = ['1,-2', '-1,3'];

        $spider = new Spider();

        $projectedPossibleMovements = $spider->getPossibleMoves(GameManager::getBoard(), '-1,0');

        $this->assertEquals(count($actualPossibleMovements), count($projectedPossibleMovements));

        foreach ($projectedPossibleMovements as $place) {
            $this->assertContains($place, $actualPossibleMovements);
        }
    }

    public function testCantMoveToOwnPosition()
    {
        $this->gameManager->play('Q', '0,0');
        $this->gameManager->play('Q', '1,0');
        $this->gameManager->play('S', '-1,0');
        $this->gameManager->play('A', '2,0');
        $this->gameManager->move('-1,0', '-1,0');

        $this->assertEquals(0, GameManager::getPlayer());
        $this->assertArrayHasKey('-1,0', GameManager::getBoard());
        $this->assertEquals('S', GameManager::getBoard()['-1,0'][0][1]);
        $this->assertEquals(0, GameManager::getBoard()['-1,0'][0][0]);
    }

    public function testCantMoveToOccupiedPosition()
    {
        $this->gameManager->play('Q', '0,0');
        $this->gameManager->play('Q', '1,0');
        $this->gameManager->play('S', '-1,0');
        $this->gameManager->play('A', '2,0');
        $this->gameManager->move('-1,0', '0,0');

        $this->assertEquals(0, GameManager::getPlayer());
        $this->assertArrayHasKey('-1,0', GameManager::getBoard());
        $this->assertEquals('S', GameManager::getBoard()['-1,0'][0][1]);
        $this->assertEquals(0, GameManager::getBoard()['-1,0'][0][0]);
        $this->assertArrayHasKey('0,0', GameManager::getBoard());
        $this->assertEquals('Q', GameManager::getBoard()['0,0'][0][1]);
        $this->assertEquals(0, GameManager::getBoard()['0,0'][0][0]);
    }
}