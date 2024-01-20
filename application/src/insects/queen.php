<?php

namespace insects;

use helpers\MoveHelper;
use helpers\RuleHelper;

class Queen extends Insect
{
    /**
     * The Queen can move 1 step per turn in any direction.
     * The new position must be adjacent to another piece.
     * The hive must remain connected.
     * The piece has to slide, which can be checked in util.slide(board, from, to)
     */
    function getPossibleMoves($board, $from): array
    {
        if (self::isBlockedByBeetle($board, $from)) {
            return [];
        }

        $to = [];
        $neighbours = MoveHelper::getNeighbours($from);

        foreach ($neighbours as $neighbour) {
            if (RuleHelper::hasNeighBour($neighbour, $board, [$from]) && !isset($board[$neighbour])) {
                $to[] = $neighbour;
            }
        }

        return $to;
    }
}