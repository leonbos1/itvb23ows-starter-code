<?php

namespace insects;

use helpers\RuleHelper;
use managers\GameManager;

class Grasshopper implements Insect
{
    public function getPossibleMoves($board, $from)
    {
        $fromExploded = explode(',', $from);
        $possibleMoves = [];
        $offsets = GameManager::$offsets;

        foreach ($offsets as $offset) {
            $position = $this->getFirstEmptyPosition($fromExploded, $offset, $board);
            if ($position) {
                $possibleMoves[] = $position;
            }
        }

        return $possibleMoves;
    }

    private function getFirstEmptyPosition($fromExploded, $offset, $board)
    {
        $p = $fromExploded[0] + $offset[0];
        $q = $fromExploded[1] + $offset[1];
        $position = $p . "," . $q;

        if (!isset($board[$position])) {
            return null; 
        }

        while (isset($board[$position])) {
            $p += $offset[0];
            $q += $offset[1];
            $position = $p . "," . $q;
        }

        return $position;
    }
}