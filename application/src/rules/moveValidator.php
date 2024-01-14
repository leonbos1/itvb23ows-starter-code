<?php

function boardPositionIsEmpy($board, $from)
{
    return !isset($board[$from]);
}

function tileNotOwnedByPlayer($board, $from, $player)
{
    return $board[$from][count($board[$from]) - 1][0] != $player;
}