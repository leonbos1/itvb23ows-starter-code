<?php

namespace insects;

abstract class Insect
{
    public abstract function getPossibleMoves($board, $from);

    public function isBlockedByBeetle($board, $from) {
        return isset($board[$from]) && count($board[$from]) > 1;
    }
}
