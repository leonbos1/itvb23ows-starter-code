<?php

namespace tests;

use helpers\MoveHelper;
use managers\GameManager;
use mock\MockDatabaseManager;
use PHPUnit\Framework\TestCase;

class NeighboursTest extends TestCase
{
    private GameManager $gameManager;

    protected function setUp(): void
    {
        $this->gameManager = new GameManager(new MockDatabaseManager());

        $this->gameManager->restart();
    }

    public function testNeighboursSimple()
    {
        $neighbours = MoveHelper::getNeighbours('0,0');

        $actualNeighbours = ['0,1', '0,-1', '1,0', '-1,0', '-1,1', '1,-1'];

        $this->assertEquals(count($actualNeighbours), count($neighbours));

        foreach ($neighbours as $neighbour) {
            $this->assertContains($neighbour, $actualNeighbours);
        }
    }


    public function testNeighbours()
    {
        $actualNeighbours = ['1,1', '0,2', '-1,2', '-1,1', '0,0', '1,0'];

        $projectedNeighbours = MoveHelper::getNeighbours('0,1');

        $this->assertEquals(count($actualNeighbours), count($projectedNeighbours));

        foreach ($projectedNeighbours as $place) {
            $this->assertContains($place, $actualNeighbours);
        }
    }

    public function testNeighboursSameColorSimple()
    {
        $this->gameManager->play('Q', '0,0');
        $this->gameManager->play('A', '0,1');
        $this->gameManager->play('A', '1,-1');

        $neighbours = MoveHelper::getNeighboursSameColor(GameManager::getBoard(), '0', '0,0');

        $actualNeighbours = ['1,-1'];

        $this->assertNotEmpty($neighbours);
        $this->assertEquals(count($actualNeighbours), count($neighbours));

        foreach ($neighbours as $neighbour) {
            $this->assertContains($neighbour, $actualNeighbours);
        }
    }

    public function testIsNeighbour()
    {
        $this->assertTrue(MoveHelper::isNeighbour('0,0', '0,1'));
        $this->assertTrue(MoveHelper::isNeighbour('0,0', '0,-1'));
        $this->assertTrue(MoveHelper::isNeighbour('0,0', '1,0'));
        $this->assertTrue(MoveHelper::isNeighbour('0,0', '-1,0'));
        $this->assertTrue(MoveHelper::isNeighbour('0,0', '-1,1'));
        $this->assertTrue(MoveHelper::isNeighbour('0,0', '1,-1'));

        $this->assertFalse(MoveHelper::isNeighbour('0,0', '1,1'));
        $this->assertFalse(MoveHelper::isNeighbour('0,0', '2,0'));
        $this->assertFalse(MoveHelper::isNeighbour('0,0', '0,2'));
        $this->assertFalse(MoveHelper::isNeighbour('0,0', '2,2'));
        $this->assertFalse(MoveHelper::isNeighbour('0,0', '-2,-2'));
        $this->assertFalse(MoveHelper::isNeighbour('2,-1', '0,1'));
    }
}