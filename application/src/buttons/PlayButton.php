<?php

namespace buttons;

class PlayButton extends Button
{
    public function execute()
    {
        $piece = $_POST['piece'];
        $move = $_POST['to'];

        $this->gameManager->play($piece, $move);

        header('Location: /');
    }
}