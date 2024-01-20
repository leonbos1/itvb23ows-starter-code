<?php

namespace helpers;

use managers\GameManager;

class MoveHelper
{
    private function __construct()
    {
    }

    public static function getPossiblePlacements($board, $player)
    {
        if (self::isFirstMove($board)) {
            return ['0,0'];
        }

        if (self::isSecondMove($board)) {
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
            $neighbours = self::getNeighbours($tile);
            $allNeighbours = array_merge($allNeighbours, $neighbours);
        }

        $allNeighbours = array_unique($allNeighbours);

        foreach ($allNeighbours as $neighbour) {
            if (!isset($board[$neighbour]) && self::neighboursAreSameColor($player, $neighbour, $board)) {
                $to[] = $neighbour;
            }
        }

        return $to;
    }


    /**
     * Checks if all the neighbours of a given position are of the same color.
     * 
     * @param int $player The player to check for
     * @param string $a The position to check
     * @param array $board The current board state
     */
    public static function neighboursAreSameColor($player, $position, $board)
    {
        $neighbours = MoveHelper::getNeighbours($position);

        foreach ($neighbours as $neighbour) {
            if (isset($board[$neighbour]) && $board[$neighbour][count($board[$neighbour]) - 1][0] != $player)
                return false;
        }

        return true;
    }


    public static function len($tileStack): int
    {
        if (!$tileStack)
            return 0;

        return count($tileStack);
    }


    /**
     * Get the positions of all the neighbours of a given coordinate.
     * 
     * @param string $coordinate The coordinate to get the neighbours of
     * @return array An array of coordinates
     
     */
    public static function getNeighbours($coordinate)
    {
        $neighbours = [];
        foreach (GameManager::$offsets as $pq) {
            $pq2 = explode(',', $coordinate);
            $neighbours[] = ($pq[0] + $pq2[0]) . ',' . ($pq[1] + $pq2[1]);
        }
        return $neighbours;
    }


    /**
     * Get the positions of all the neighbours of a given coordinate that have the same color.
     */
    public static function getNeighboursSameColor($board, $player, $coordinate)
    {
        $neighbours = self::getNeighbours($coordinate);
        $neighboursSameColor = [];

        foreach ($neighbours as $neighbour) {
            if (isset($board[$neighbour]) && $board[$neighbour][count($board[$neighbour]) - 1][0] == $player)
                $neighboursSameColor[] = $neighbour;
        }

        return $neighboursSameColor;
    }

    public static function isFirstMove($board)
    {
        return count($board) == 0;
    }


    public static function isSecondMove($board)
    {
        return count($board) == 1;
    }


    public static function isNeighbour($a, $b)
    {
        $a = explode(',', $a);
        $b = explode(',', $b);

        $dx = $a[0] - $b[0];
        $dy = $a[1] - $b[1];

        foreach (GameManager::$offsets as $offset) {
            if ($dx == $offset[0] && $dy == $offset[1]) {
                return true;
            }
        }

        return false;
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
    public static function getBoundaryTiles($board, $exclude = []): array
    {
        $to = [];
        foreach (GameManager::$offsets as $pq) {
            foreach (array_keys($board) as $pos) {
                if (!in_array($pos, $exclude)) {
                    $pq2 = explode(',', $pos);
                    if (RuleHelper::hasNeighBour($pos, $board)) {
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

    public static function moveNotPossible()
    {
        $board = GameManager::getBoard();

        if (count($board) == 0) {
            return false;
        }

        $playerTiles = [];

        foreach ($board as $position => $tiles) {
            foreach ($tiles as $tile) {
                if ($tile[0] == GameManager::getPlayer()) {
                    $playerTiles[] = $position;
                }
            }
        }

        foreach ($playerTiles as $position) {
            $insect = InsectHelper::getInsectInstance($board[$position][0][1]);
            $possibleMoves = $insect->getPossibleMoves($board, $position);
            if (count($possibleMoves) == 0) {
                return true;
            }
        }
    }

    public static function playNotPossible()
    {
        $player = GameManager::getPlayer();

        $hand = GameManager::getHand($player);

        $tilesOnBoard = [];

        foreach (GameManager::getBoard() as $position => $tiles) {
            foreach ($tiles as $tile) {
                if ($tile[0] == $player) {
                    $tilesOnBoard[] = $position;
                }
            }
        }

        foreach ($hand as $piece => $count) {
            if ($count > 0) {
                foreach ($tilesOnBoard as $position) {
                    $neighbours = MoveHelper::getNeighbours($position);

                    foreach ($neighbours as $neighbour) {
                        if (RuleHelper::isValidPlay($piece, $neighbour)) {
                            return false;
                        }
                    }
                }
            }
        }
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
    public static function outlineTrace($state, $coordinate, $steps = 1)
    {
        $contour = self::getBoundaryTiles($state, [$coordinate]);

        $visited = [];
        $todo = [[$coordinate, 0]];
        $return = [];


        while ($todo) {
            [$c, $n] = array_pop($todo);
            foreach (self::getNeighbours($c) as $neighbour) {
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
    public static function canPlayPiece($pos, $board)
    {
        if (isset($board[$pos])) {
            return false;
        }
        if (!RuleHelper::hasNeighBour($pos, $board)) {
            return false;
        }

        if (self::neighboursAreSameColor(GameManager::getPlayer(), $pos, $board)) {
            return true;
        }

        return false;
    }
}