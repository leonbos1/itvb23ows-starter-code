<?php

namespace insects;
use managers\GameManager;

abstract class Insect
{
    public abstract function getPossibleMoves($board, $from);

    public function isBlockedByBeetle($board, $from) {
        if (!isset($board[$from])) {
            return false;
        }

        $currentStackCount = count($board[$from]);

        if ($currentStackCount <= 1) {
            return false;
        }

        if ($board[$from][$currentStackCount][0] == GameManager::getPlayer()) {
            return false;
        }

        return true;
    }
}
