<?php

namespace tests;

use helpers\RuleHelper;
use managers\GameManager;
use mock\MockDatabaseManager;
use PHPUnit\Framework\TestCase;

class HasNeighbourTest extends TestCase
{
    private GameManager $gameManager;

    protected function setUp(): void
    {
        $this->gameManager = new GameManager(new MockDatabaseManager());
    }

    public function testHasNeighbour()
    {
        $this->gameManager->play('Q', '0,0');
        $this->gameManager->play('Q', '0,1');
        $this->gameManager->play('A', '1,-1');
        $this->gameManager->play('A', '-1,2');

        $hasNeighbour = RuleHelper::hasNeighbour('0,0', GameManager::getBoard());

        $this->assertTrue($hasNeighbour);
    }
}