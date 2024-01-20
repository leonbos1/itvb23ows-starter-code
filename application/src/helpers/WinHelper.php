<?php

namespace helpers;

use managers\GameManager;

class WinHelper
{
    public static function isGameOver()
    {
        return self::QueenIsSurrounded(0) || self::QueenIsSurrounded(1);
    }

    public static function gameTied()
    {
        $whiteWon = self::QueenIsSurrounded(0);
        $blackWon = self::QueenIsSurrounded(1);

        return $whiteWon && $blackWon;
    }

    public static function getWinner()
    {
        $whiteWon = self::QueenIsSurrounded(0);
        $blackWon = self::QueenIsSurrounded(1);

        if ($whiteWon && !$blackWon) {
            return 0;
        }

        if (!$whiteWon && $blackWon) {
            return 1;
        }

        return null;
    }

    public static function QueenIsSurrounded($player)
    {
        $board = GameManager::getBoard();

        $queenPosition = null;

        foreach ($board as $position => $tiles) {
            foreach ($tiles as $tile) {
                if ($tile[0] == $player && $tile[1] == "Q") {
                    $queenPosition = $position;
                }
            }
        }

        if ($queenPosition == null) {
            return false;
        }

        $neighbours = MoveHelper::getNeighbours($queenPosition);

        foreach ($neighbours as $neighbour) {
            if (!isset($board[$neighbour])) {
                return false;
            }
        }

        return true;
    }
}