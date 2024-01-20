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
}