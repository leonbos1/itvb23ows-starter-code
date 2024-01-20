<?php

namespace insects;

use helpers\MoveHelper;
use helpers\RuleHelper;
use managers\GameManager;

class Spider implements Insect
{
    public function getPossibleMoves($board, $from)
    {
        $visitedPositions = [];
        $lastVisitedPosition = null;
        $depth = 0;
        $tiles = array($from);
        $tiles[] = null;
        $possibleMoves = [];

        unset($board[$from]);

        while (count($tiles) > 0 && $depth < 3) {
            $currentTile = array_shift($tiles);

            if ($currentTile == null) {
                $depth++;
                $tiles[] = null;
                if (reset($tiles) == null) {
                    break;
                } else {
                    continue;
                }
            }

            if (!in_array($currentTile, $visitedPositions)) {
                $visitedPositions[] = $currentTile;
            }

            $pointArray = explode(',', $currentTile);

            foreach (GameManager::$offsets as $pq) {
                $p = $pointArray[0] + $pq[0];
                $q = $pointArray[1] + $pq[1];

                $point = $p . "," . $q;

                if (self::isValid($lastVisitedPosition, $point, $board, $visitedPositions)) {
                    if ($depth == 2) {
                        $possibleMoves[] = $point;
                    }
                    $tiles[] = $point;
                }
            }

            $lastVisitedPosition = $currentTile;
        }

        return $possibleMoves;
    }

    /**
     * Function to validate that the tile is a valid position for the spider to move to
     * 
     * @param string $prevTile
     * @param string $currentTile
     * @param array $board
     * @param array $visited
     * 
     * @return bool
     */
    private function isValid($prevTile, $currentTile, $board, $visited)
    {
        return array_search($currentTile, $visited) === false
            && $currentTile != $prevTile
            && !isset($board[$currentTile])
            && RuleHelper::hasNeighbour($currentTile, $board);
    }
}