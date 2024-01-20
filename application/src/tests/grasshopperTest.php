<?php

namespace tests;

use managers\GameManager;
use mock\MockDatabaseManager;
use PHPUnit\Framework\TestCase;

class GrasshopperTest extends TestCase
{
    private GameManager $gameManager;

    protected function setUp(): void
    {
        $this->gameManager = new GameManager(new MockDatabaseManager());

        $this->gameManager->restart();
    }

    public function testJumpDiagonal()
    {
        $this->gameManager->play('G', '0,0');
        $this->gameManager->play('Q', '0,1');
        $this->gameManager->play('Q', '1,-1');
        $this->gameManager->play('A', '1,1');
        $this->gameManager->move('1,-1', '1,0');
        $this->gameManager->play('G', '2,1');
        $this->gameManager->move('0,0', '2,0');

        $this->assertEquals(count(GameManager::getBoard()), 5);

        $this->assertArrayNotHasKey('0,0', GameManager::getBoard());
        $this->assertArrayHasKey('2,0', GameManager::getBoard());
        $this->assertEquals(GameManager::getBoard()['2,0'][0][1], 'G');
    }

    public function testJumpVertical()
    {
        $this->gameManager->play('G', '0,0');
        $this->gameManager->play('Q', '0,1');
        $this->gameManager->play('Q', '1,-1');
        $this->gameManager->play('A', '1,1');
        $this->gameManager->move('1,-1', '1,0');
        $this->gameManager->play('G', '2,1');
        $this->gameManager->move('0,0', '0,2');

        $this->assertEquals(count(GameManager::getBoard()), 5);

        $this->assertArrayNotHasKey('0,0', GameManager::getBoard());
        $this->assertArrayHasKey('0,2', GameManager::getBoard());
        $this->assertEquals(GameManager::getBoard()['0,2'][0][1], 'G');
    }

    public function testNotJumpOverEmptyTile()
    {
        $this->gameManager->play('G', '0,0');
        $this->gameManager->play('Q', '-1,0');
        $this->gameManager->play('Q', '-1,1');

        $board = [
            '0,0' => [['0', 'G']],
            '-1,0' => [['0', 'Q']],
            '-1,1' => [['1', 'Q']],
            '-1,2' => [['1', 'A']],
            '0,2' => [['1', 'G']],
        ];

        GameManager::setBoard($board);
        //0,1 is empty, 0.2 is not: should not be able to jump over 0,1
        $this->gameManager->move('0,0', '0,3');

        $this->assertEquals(count(GameManager::getBoard()), 5);
        $this->assertArrayNotHasKey('0,3', GameManager::getBoard());
        $this->assertArrayHasKey('0,0', GameManager::getBoard());
        $this->assertEquals(GameManager::getBoard()['0,0'][0][1], 'G');
        $this->assertEquals(GameManager::getBoard()['0,0'][0][0], 0);
    }

    public function testNotJumpOntoAnotherTile() {
        $this->gameManager->play('G', '0,0');
        $this->gameManager->play('Q', '-1,0');
        $this->gameManager->play('Q', '0,1');
        $this->gameManager->move('0,0', '0,1');

        $this->assertArrayHasKey('0,0', GameManager::getBoard());
        $this->assertArrayHasKey('0,1', GameManager::getBoard());
        $this->assertEquals(GameManager::getBoard()['0,0'][0][1], 'G');
        $this->assertEquals(GameManager::getBoard()['0,0'][0][0], 0);
        $this->assertEquals(GameManager::getBoard()['0,1'][0][1], 'Q');
        $this->assertEquals(GameManager::getBoard()['0,1'][0][0], 0);
    }

    public function testJumpAtleastOver1Tile() {
        $this->gameManager->play('G', '0,0');
        $this->gameManager->play('Q', '-1,0');
        $this->gameManager->play('Q', '0,1');
        $this->gameManager->play('A', '-2,0');
        $this->gameManager->move('0,1', '-1,1');
        $this->gameManager->play('A', '-3,0');
        $this->gameManager->move('0,0', '0,-1');

        $this->assertArrayNotHasKey('0,-1', GameManager::getBoard());
        $this->assertArrayHasKey('0,0', GameManager::getBoard());
        $this->assertEquals(GameManager::getBoard()['0,0'][0][1], 'G');
        $this->assertEquals(GameManager::getBoard()['0,0'][0][0], 0);
    }
}
