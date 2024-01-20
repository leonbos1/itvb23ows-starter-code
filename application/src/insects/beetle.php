<?php

namespace insects;

use helpers\MoveHelper;
use helpers\RuleHelper;
use managers\GameManager;

class Beetle extends Insect
{
    function getPossibleMoves($board, $from): array
    {
        if (self::isBlockedByBeetle($board, $from)) {
            return [];
        }
        
        $to = [];
        $neighbours = MoveHelper::getNeighbours($from);

        foreach ($neighbours as $neighbour) {
            if (RuleHelper::hasNeighBour($neighbour, $board, [$from])) {
                // $board[$neighbour][0][0] != GameManager::getPlayer()
                if (isset($board[$neighbour]) && $board[$neighbour][0][0] == GameManager::getPlayer()) {
                    continue;
                }
                $to[] = $neighbour;
            }
        }

        return $to;
    }
}
