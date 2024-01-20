<?php

namespace tests;

use managers\GameManager;
use mock\MockDatabaseManager;
use PHPUnit\Framework\TestCase;
use insects\Queen;

class QueenTest extends TestCase
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
        $this->gameManager->play('Q', '1,-1');
        $this->gameManager->play('A', '-1,2');

        $actualPossibleMovements = ['1,0', '0,-1'];

        $queen = new Queen();

        $projectedPossibleMovements = $queen->getPossibleMoves(GameManager::getBoard(), '1,-1');

        $this->assertEquals(count($actualPossibleMovements), count($projectedPossibleMovements));

        foreach ($projectedPossibleMovements as $place) {
            $this->assertContains($place, $actualPossibleMovements);
        }
    }

    public function testSplitHiveMovement()
    {
        $this->gameManager->play('Q', '0,0');
        $this->gameManager->play('Q', '1,0');
        $this->gameManager->play('A', '-1,0');
        $this->gameManager->play('A', '2,0');
        $this->gameManager->move('0,0', '0,-1');

        $this->assertArrayHasKey('0,0', GameManager::getBoard());
        $this->assertArrayHasKey('1,0', GameManager::getBoard());
        $this->assertArrayHasKey('-1,0', GameManager::getBoard());
        $this->assertArrayHasKey('2,0', GameManager::getBoard());
        $this->assertArrayNotHasKey('0,-1', GameManager::getBoard());
    }
}