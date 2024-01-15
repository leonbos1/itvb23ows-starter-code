<?php

include_once dirname(__FILE__) . '/../rules/moveHelper.php';
include_once dirname(__FILE__) . '/../util.php';

class Queen implements Insect
{
    /**
     * The Queen can move 1 step per turn in any direction.
     * The new position must be adjacent to another piece.
     * The hive must remain connected.
     * The piece has to slide, which can be checked in util.slide(board, from, to)
     */
    function getPossibleMoves($board, $from): array
    {
        if (isBeetleBlocked($board, $from)) {
            return [];
        }

        $to = [];

        $neighbours = getNeighbours($from);

        foreach ($neighbours as $neighbour) {
            if (hasNeighBour($neighbour, $board, [$from]) && !isset($board[$neighbour])) {
                if (!hiveWillSplit($board, $from, $neighbour)) {
                    $to[] = $neighbour;
                }
            }
        }

        return $to;
    }
}