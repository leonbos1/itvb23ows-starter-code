<?php

namespace helpers;

use insects\Grasshopper;
use insects\Ant;
use managers\GameManager;
use helpers\MoveHelper;

class RuleHelper
{
    private function __construct()
    {
    }

    /**
     * Checks if a tile is able to slide from position $from to position $to.
     * 
     * @param string $from The position of the insect to move
     * @param string $to The position to move to
     * @param array $board The current board state
     * @return bool True if the slide is possible, false otherwise
     */
    public static function slide($from, $to, $board)
    {
        if (!self::hasValidNeighbour($from, $to, $board)) {
            return false;
        }

        list($x, $y) = explode(',', $to);
        $commonPoints = self::getCommonPoints($x, $y, $from);

        if (!self::areCommonPointsValid($commonPoints, $board) || !self::isPointValid($from, $board) || !self::isPointValid($to, $board)) {
            return false;
        }

        return self::isMoveLengthValid($commonPoints, $from, $to, $board);
    }

    public static function hasValidNeighbour($from, $to, $board)
    {
        return self::hasNeighbour($to, $board) && MoveHelper::isNeighbour($from, $to);
    }

    public static function getCommonPoints($x, $y, $from)
    {
        $commonPoints = [];
        foreach (GameManager::$offsets as $offset) {
            $newX = $x + $offset[0];
            $newY = $y + $offset[1];
            $point = $newX . "," . $newY;
            if (MoveHelper::isNeighbour($from, $point)) {
                $commonPoints[] = $point;
            }
        }
        return $commonPoints;
    }

    public static function areCommonPointsValid($commonPoints, $board)
    {
        foreach ($commonPoints as $point) {
            if (isset($board[$point]) && $board[$point]) {
                return true;
            }
        }
        return false;
    }

    public static function isPointValid($point, $board)
    {
        return isset($board[$point]) && $board[$point];
    }

    public static function getLength($point, $board)
    {
        $length = $board[$point] ?? 0;
        return MoveHelper::len($length);
    }

    public static function isMoveLengthValid($commonPoints, $from, $to, $board)
    {
        $firstCommonLen = self::getLength($commonPoints[0], $board);
        $secondCommonLen = self::getLength($commonPoints[1], $board);
        $fromLen = self::getLength($from, $board);
        $toLen = self::getLength($to, $board);

        return min($firstCommonLen, $secondCommonLen) <= max($fromLen, $toLen);
    }


    public static function tileInHand($board, $player, $from): bool
    {
        return $board[$from][count($board[$from]) - 1][0] == $player;
    }

    public static function playerMustPlayQueen($piece, $board, $hand): bool
    {
        return $piece != 'Q' && array_sum($hand) <= 8 && $hand['Q'];
    }


    /**
     * Checks if hive is split when a tile is moved.
     */
    public static function hiveWillSplit($board)
    {
        $all = array_keys($board);
        $queue = [array_shift($all)];

        while ($queue) {
            $next = explode(',', array_shift($queue));
            foreach (GameManager::$offsets as $pq) {
                list($p, $q) = $pq;
                $p += $next[0];
                $q += $next[1];

                $position = $p . "," . $q;

                if (in_array($position, $all)) {
                    $queue[] = $position;
                    $all = array_diff($all, [$position]);
                }
            }
        }

        return $all;
    }


    public static function isValidPlay($piece, $to)
    {
        $player = GameManager::getPlayer();
        $board = GameManager::getBoard();
        $hand = GameManager::getHand($player);

        if (!$hand[$piece])
            GameManager::setError("Player does not have tile");
        elseif (isset($board[$to]))
            GameManager::setError("Board position is not empty");
        elseif (count($board) && !self::hasNeighBour($to, $board))
            GameManager::setError("board position has no neighbour");
        elseif (array_sum($hand) < 11 && !MoveHelper::neighboursAreSameColor($player, $to, $board))
            GameManager::setError("Board position has opposing neighbour");
        elseif (self::playerMustPlayQueen($piece, $board, $hand)) {
            GameManager::setError("Must play queen bee");
        } else {
            return true;
        }

        return false;
    }


    /**
     * Checks if a given move is valid.
     * 
     * @param string $from The position of the insect to move
     * @param string $to The position to move to
     * 
     * @return bool True if the move is valid, false otherwise
     */
    public static function isValidMove($from, $to): bool
    {
        $board = GameManager::getBoard();
        $player = GameManager::getPlayer();
        $hand = GameManager::getHand($player);

        if ($from == $to) {
            $_SESSION['error'] = 'Tile must move';
        } elseif (!isset($board[$from])) {
            $_SESSION['error'] = 'Board position is empty';
        } elseif (
            isset($board[$from][count($board[$from]) - 1]) &&
            $board[$from][count($board[$from]) - 1][0] != $player
        )
            $_SESSION['error'] = "Tile is not owned by player";
        elseif ($hand['Q'])
            $_SESSION['error'] = "Queen bee is not played";
        else {
            $tile = array_pop($board[$from]);
            unset($board[$from]);

            if (isset($board[$to]) && self::isBeetle($tile)) {
                $_SESSION['error'] = "Tile is already taken";
            } elseif (!RuleHelper::hasNeighBour($to, $board) || RuleHelper::hiveWillSplit($board)) {
                $_SESSION['error'] = "Move would split hive";
            } elseif (RuleHelper::slide($from, $to, $board)) {
                $_SESSION['error'] = "Slide is not allowed";
            } elseif ($tile[1] == 'G') {
                $gh = new Grasshopper();
                $validMoves = $gh->getPossibleMoves($board, $from);
                if (!in_array($to, $validMoves)) {
                    $_SESSION['error'] = "Grasshopper cannot jump";
                } else {
                    return true;
                } 
            } elseif ($tile[1] == 'A') {
                $ant = new Ant();
                $validMoves = $ant->getPossibleMoves($board, $from);
                if (!in_array($to, $validMoves)) {
                    $_SESSION['error'] = "Ant cannot move to this tile";
                } else {
                    return true;
                }
            }
            else {
                return true;
            }
        }

        return false;
    }


    public static function isBeetle($piece)
    {
        return $piece[1] == 'B';
    }

    /**
     * Checks if a given position has a neighbour.
     * 
     * @param string $position The position to check
     * @param array $board The current board state
     * @return bool True if the position has a neighbour, false otherwise
     */
    public static function hasNeighbour($position, $board, $exclude = [])
    {
        foreach (array_keys($board) as $board_position) {
            if (in_array($board_position, $exclude)) {
                continue;
            }
            if (MoveHelper::isNeighbour($position, $board_position)) {
                return true;
            }
        }

        return false;
    }
}