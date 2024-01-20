<?php

namespace insects;

use helpers\RuleHelper;
use managers\GameManager;

class Ant implements Insect
{
    public function getPossibleMoves($board, $from)
    {
        $board = GameManager::getBoard();
        unset($board[$from]);

        $visited = [];
        $tiles = array($from);
        $possibleMoves = [];

        while (!empty($tiles)) {
            $currentTile = array_shift($tiles);

            if (!in_array($currentTile, $visited)) {
                $visited[] = $currentTile;
            }

            $b = explode(',', $currentTile);

            foreach (GameManager::$offsets as $pq) {
                $p = $b[0] + $pq[0];
                $q = $b[1] + $pq[1];

                $point = $p . "," . $q;

                if (!in_array($point, $visited) && !isset($board[$point]) && RuleHelper::hasNeighbour($point, $board)) {
                    $possibleMoves[] = $point;
                    $tiles[] = $point;
                }
            }
        }

        return array_unique($possibleMoves);
    }
}