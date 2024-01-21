<?php

namespace buttons;

class AiButton extends Button
{
    public function execute()
    {
        $this->gameManager->getAiMove();

        header('Location: /');
    }
}