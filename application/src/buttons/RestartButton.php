<?php

namespace buttons;

class RestartButton extends Button
{
    public function execute()
    {
        $this->gameManager->restart();

        header('Location: /');
    }
}