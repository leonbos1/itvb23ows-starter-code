<?php

$GLOBALS['OFFSETS'] = [[0, 1], [0, -1], [1, 0], [-1, 0], [-1, 1], [1, -1]];

function isNeighbour($a, $b)
{
    $a = explode(',', $a);
    $b = explode(',', $b);
    if ($a[0] == $b[0] && abs($a[1] - $b[1]) == 1)
        return true;
    if ($a[1] == $b[1] && abs($a[0] - $b[0]) == 1)
        return true;
    if ($a[0] + $a[1] == $b[0] + $b[1])
        return true;
    return false;
}


/**
 * Checks if a given position has a neighbour.
 * 
 * @param string $position The position to check
 * @param array $board The current board state
 * @return bool True if the position has a neighbour, false otherwise
 */
function hasNeighBour($position, $board)
{
    foreach (array_keys($board) as $board_position) {
        if (isNeighbour($position, $board_position))
            return true;
    }

    return false;
}


/**
 * Checks if all the neighbours of a given position are of the same color.
 * 
 * @param int $player The player to check for
 * @param string $a The position to check
 * @param array $board The current board state
 */
function neighboursAreSameColor($player, $position, $board)
{
    $neighbours = getNeighbours($position);

    foreach ($neighbours as $neighbour) {
        if (isset($board[$neighbour]) && $board[$neighbour][count($board[$neighbour]) - 1][0] != $player)
            return false;
    }

    return true;
}

/**
 * Checks if a slide is possible
 * @param array $board The current board state
 * @param string $from The position of the insect to move
 * @param string $to The position to move to
 * @return bool True if the slide is possible, false otherwise
 */
function slide($board, $from, $to)
{
    if (!hasNeighBour($to, $board))
        return false;
    if (!isNeighbour($from, $to))
        return false;

    $b = explode(',', $to);
    $common = [];

    foreach ($GLOBALS['OFFSETS'] as $pq) {
        $p = $b[0] + $pq[0];
        $q = $b[1] + $pq[1];
        if (isNeighbour($from, $p . "," . $q) && array_key_exists($p . "," . $q, $board))
            $common[] = $p . "," . $q;
    }

    if (!$board[$common[0]] && !$board[$common[1]] && !$board[$from] && !$board[$to])
        return false;

    return min(len($board[$common[0]]), len($board[$common[1]])) <= max(len($board[$from]), len($board[$to]));
}


function len($tileStack): int
{
    return count($tileStack);
}


function tileInHand($board, $player, $from): bool
{
    return $board[$from][count($board[$from]) - 1][0] == $player;
}


/**
 * Get the positions of all the neighbours of a given coordinate.
 * 
 * @param string $coordinate The coordinate to get the neighbours of
 * @return array An array of coordinates
 
 */
function getNeighbours($coordinate)
{
    $neighbours = [];
    foreach ($GLOBALS['OFFSETS'] as $pq) {
        $pq2 = explode(',', $coordinate);
        $neighbours[] = ($pq[0] + $pq2[0]) . ',' . ($pq[1] + $pq2[1]);
    }
    return $neighbours;
}


/**
 * Get the positions of all the neighbours of a given coordinate that have the same color.
 */
function getNeighboursSameColor($coordinate, $color)
{
    $neighbours = [];
    foreach (getNeighbours($coordinate) as $neighbour) {
        if (isset($GLOBALS['board'][$neighbour]) && $GLOBALS['board'][$neighbour][count($GLOBALS['board'][$neighbour]) - 1][0] == $color) {
            $neighbours[] = $neighbour;
        }
    }
    return $neighbours;
}


/**
 * Checks if hive is split when a tile is moved.
 * 
 * @param array $board The current board state
 * @param string $from The position of the insect to move
 * @param string $to The position to move to
 * @return bool True if the hive is split, false otherwise
 */
function hiveIsSplit($board, $from, $to)
{
    $all = array_keys($board);
    $queue = [array_shift($all)];
    while ($queue) {
        $next = explode(',', array_shift($queue));
        foreach ($GLOBALS['OFFSETS'] as $pq) {
            list($p, $q) = $pq;
            $p += $next[0];
            $q += $next[1];
            if (in_array("$p,$q", $all)) {
                $queue[] = "$p,$q";
                $all = array_diff($all, ["$p,$q"]);
            }
        }
    }
    if ($all) {
        return true;
    }
    return false;
}

?>