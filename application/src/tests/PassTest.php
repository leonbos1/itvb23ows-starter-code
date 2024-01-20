<?php

namespace tests;

use managers\GameManager;
use mock\MockDatabaseManager;
use PHPUnit\Framework\TestCase;

class PassTest extends TestCase
{
    private GameManager $gameManager;

    protected function setUp(): void
    {
        $this->gameManager = new GameManager(new MockDatabaseManager());

        $this->gameManager->restart();
    }

    public function testPassNotAllowed()
    {
        $this->gameManager->play('A', '0,0');
        $this->gameManager->play('A', '0,1');
        $this->gameManager->play('B', '1,-1');
        $this->gameManager->play('A', '-1,2');
        $this->gameManager->pass();

        $this->assertEquals(0, $this->gameManager->getPlayer());
    }

    public function testHasToPass()
    {
        $this->gameManager->play('A', '0,0');
        $this->gameManager->play('Q', '-1,1');
        $this->gameManager->play('B', '0,-1');
        $this->gameManager->move('-1,1', '-1,0');
        $this->gameManager->move('0,-1', '-1,0');
        $this->gameManager->pass();

        $this->assertEquals(0, $this->gameManager->getPlayer());
    }
}