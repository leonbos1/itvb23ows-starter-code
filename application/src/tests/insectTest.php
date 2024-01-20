<?php

namespace tests;

use insects\Insect;
use managers\GameManager;
use PHPUnit\Framework\TestCase;
use helpers\InsectHelper;
use mock\MockDatabaseManager;

class InsectTest extends TestCase
{
    private GameManager $gameManager;

    protected function setUp(): void
    {
        $this->gameManager = new GameManager(new MockDatabaseManager());
    }
    
    public function testValidInsectType()
    {
        $validTypes = ['A', 'B', 'G', 'Q', 'S'];

        foreach ($validTypes as $type) {
            $insect = InsectHelper::getInsectInstance($type);
            $this->assertInstanceOf(Insect::class, $insect);
        }
    }

    public function testInvalidInsectType()
    {
        $invalidTypes = ['d', '1', '!', ''];

        foreach ($invalidTypes as $type) {
            $insect = InsectHelper::getInsectInstance($type);
            $this->assertNull($insect);
        }
    }

    public function testLowerCasedInsectType()
    {
        $insect = InsectHelper::getInsectInstance('a');
        $this->assertInstanceOf(Insect::class, $insect);
    }
}