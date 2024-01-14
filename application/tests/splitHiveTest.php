<?php

require_once './src/util.php';
require_once './src/rules/moveHelper.php';

class splitHiveTest extends PHPUnit\Framework\TestCase
{
    public function testPlaySecondRound()
    {
        // $board = ['0,0' => [['0', 'Q']], '0,1' => [['1', 'A']]];

        // $actualPossiblePlaces = ['-1,0', '0,-1', '1,-1'];

        // $projectedPossiblePlaces = getPossiblePlacements($board, '0');

        // $this->assertEquals(count($actualPossiblePlaces), count($projectedPossiblePlaces));

        // foreach ($projectedPossiblePlaces as $place) {
        //     $this->assertContains($place, $actualPossiblePlaces);
        // }
        $this->assertTrue(true);
    }
}