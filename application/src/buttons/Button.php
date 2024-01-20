<?php

namespace buttons;

use managers\GameManager;

abstract class Button
{
    protected GameManager $gameManager;

    public function __construct(GameManager $gameManager)
    {
        $this->gameManager = $gameManager;
    }

    public abstract function execute();
}