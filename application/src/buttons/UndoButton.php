<?php

namespace buttons;

class UndoButton extends Button
{
    public function execute()
    {
        $this->gameManager->undo();

        header('Location: /');
    }
}