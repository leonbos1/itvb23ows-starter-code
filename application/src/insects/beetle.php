<?php

include_once dirname(__FILE__) . '/../rules/moveHelper.php';
include_once dirname(__FILE__) . '/../util.php';
include_once dirname(__FILE__) . '/insect.php';

class Beetle implements Insect
{
    /**
     * The Beetle can move 1 step per turn in any direction.
     * The new position must be adjacent to another piece.
     * The beetle can climb on top of other pieces.
     * The hive must remain connected.
     */
    function getPossibleMoves($board, $from): array
    {
        if (isBeetleBlocked($board, $from)) {
            return [];
        }

        $to = [];

        $neighbours = getNeighbours($from);

        foreach ($neighbours as $neighbour) {
            if (hasNeighBour($neighbour, $board, [$from])) {
                $to[] = $neighbour;
            }
        }

        return $to;
    }
}