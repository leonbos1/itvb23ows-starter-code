<?php

require_once dirname(__FILE__) . "/../util.php";


function getPossiblePlacements($board, $player)
{
    if (isFirstMove($board)) {
        return ['0,0'];
    }

    if (isSecondMove($board)) {
        return ['0,1', '1,0', '-1,0', '0,-1', '1,-1', '-1,1'];
    }

    $to = [];

    $userTiles = [];

    foreach ($board as $pos => $tile) {
        if ($tile[0][0] == $player) {
            $userTiles[] = $pos;
        }
    }

    $allNeighbours = [];
    foreach ($userTiles as $tile) {
        $neighbours = getNeighbours($tile);
        $allNeighbours = array_merge($allNeighbours, $neighbours);
    }

    $allNeighbours = array_unique($allNeighbours);

    foreach ($allNeighbours as $neighbour) {
        if (!isset($board[$neighbour]) && neighboursAreSameColor($player, $neighbour, $board)) {
            $to[] = $neighbour;
        }
    }

    return $to;
}


function isFirstMove($board)
{
    return count($board) == 0;
}


function isSecondMove($board)
{
    return count($board) == 1;
}


/**
 * Computes the boundary tiles of a given game board state.
 * 
 * This function evaluates the game board and identifies the boundary positions
 * that are not occupied but are adjacent to the occupied tiles. It excludes
 * any positions provided in the ignoredPositions array.
 * 
 * @param array $board The current state of the game board, represented as an array.
 * @param array $ignoredPositions Positions to be excluded from the boundary calculation.
 * 
 * @return array Returns an array of strings, each representing a boundary position.
 */
function getBoundaryTiles($board, $exclude = []): array
{
    $to = [];
    foreach ($GLOBALS['OFFSETS'] as $pq) {
        foreach (array_keys($board) as $pos) {
            if (!in_array($pos, $exclude)) {
                $pq2 = explode(',', $pos);
                if (hasNeighBour($pos, $board)) {
                    $new_pos = ($pq[0] + $pq2[0]) . ',' . ($pq[1] + $pq2[1]);
                    if (isset($board[$new_pos])) {
                        continue;
                    }
                    $to[] = $new_pos;
                }
            }
        }
    }
    $to = array_unique($to);
    return $to;
}

/**
 * Traces a path from a given starting coordinate to a specified depth along the boundary of the game board.
 * 
 * This function is used to trace a path from a specific starting point (startCoord) along
 * the boundary of the game state. It moves up to a specified depth. The function takes into
 * account the current state of the game board and avoids revisiting already visited coordinates.
 * 
 * @param array $gameState The current state of the game board.
 * @param string $startCoord The starting coordinate for the trace, represented as a string.
 * @param int $depth The depth of the trace, indicating how far from the start coordinate the trace should go.
 * 
 * @return array Returns an array of coordinates (as strings) that are part of the traced path.
 */
function outlineTrace($state, $coordinate, $steps = 1)
{
    $contour = getBoundaryTiles($state, [$coordinate]);

    $visited = [];
    $todo = [[$coordinate, 0]];
    $return = [];


    while ($todo) {
        [$c, $n] = array_pop($todo);
        foreach (getNeighbours($c) as $neighbour) {
            if (in_array($neighbour, $contour) && !in_array($neighbour, $visited) && !array_key_exists($neighbour, $state)) {
                $visited[] = $neighbour;
                if ($n == $steps) {
                    $return[] = $c;
                } else {
                    $todo[] = [$neighbour, $n + 1];
                }
            }
        }
    }

    return array_unique($return);
}


/**
 * Helper to check if a piece can be played in a given position.
 */
function canPlayPiece($pos, $board)
{
    if (isset($board[$pos])) {
        return false;
    }
    if (!hasNeighBour($pos, $board)) {
        return false;
    }

    if (neighboursAreSameColor($_SESSION['player'], $pos, $board)) {
        return true;
    }

    return false;
}