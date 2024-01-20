<?php

namespace insects;

interface Insect
{
    public function getPossibleMoves($board, $from);
}
