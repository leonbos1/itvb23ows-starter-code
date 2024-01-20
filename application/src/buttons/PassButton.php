<?php

namespace buttons;

class PassButton extends Button
{
    public function execute()
    {
        $this->gameManager->pass();

        header('Location: /');
    }
}