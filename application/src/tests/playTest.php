<?php

namespace tests;

use helpers\MoveHelper;
use managers\GameManager;
use mock\MockDatabaseManager;
use PHPUnit\Framework\TestCase;

class playTest extends TestCase
{
    private GameManager $gameManager;

    protected function setUp(): void
    {
        $this->gameManager = new GameManager(new MockDatabaseManager());

        $this->gameManager->restart();
    }

    public function testPlaySecondRound()
    {
        $this->gameManager->play('Q', '0,0');
        $this->gameManager->play('A', '0,1');

        $actualPossiblePlaces = ['-1,0', '0,-1', '1,-1'];

        $projectedPossiblePlaces = MoveHelper::getPossiblePlacements(GameManager::getBoard(), '0');

        $this->assertEquals(count($actualPossiblePlaces), count($projectedPossiblePlaces));

        foreach ($projectedPossiblePlaces as $place) {
            $this->assertContains($place, $actualPossiblePlaces);
        }
    }

    public function testPlaySecondRound2()
    {
        $this->gameManager->play('Q', '0,0');
        $this->gameManager->play('Q', '0,1');
        $this->gameManager->play('A', '1,-1');

        $actualPossiblePlaces = ['1,1', '0,2', '-1,2'];

        $projectedPossiblePlaces = MoveHelper::getPossiblePlacements(GameManager::getBoard(), '1');

        $this->assertEquals(count($actualPossiblePlaces), count($projectedPossiblePlaces));

        foreach ($projectedPossiblePlaces as $place) {
            $this->assertContains($place, $actualPossiblePlaces);
        }
    }

    public function testPlayHiveSplit()
    {
        $this->gameManager->play('Q', '0,0');
        $this->gameManager->play('A', '0,1');

        $actualPossiblePlaces = ['-1,0', '0,-1', '1,-1'];

        $projectedPossiblePlaces = MoveHelper::getPossiblePlacements(GameManager::getBoard(), '0');

        $this->assertEquals(count($actualPossiblePlaces), count($projectedPossiblePlaces));

        foreach ($projectedPossiblePlaces as $place) {
            $this->assertContains($place, $actualPossiblePlaces);
        }
    }
}