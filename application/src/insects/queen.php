<?php

include_once "../rules/moveHelper.php";
include_once "../util.php";

class Queen implements Insect
{
    /**
     * The Queen can move 1 step per turn in any direction.
     * The new position must be adjacent to another piece of the same color.
     */
    function getPossibleMoves($board, $from): array
    {
        $to = [];
        foreach ($GLOBALS['OFFSETS'] as $pq) {
                $to[] = $from[0] + $pq[0] . ',' . $from[1] + $pq[1];
            }
        return $to;
    }
}