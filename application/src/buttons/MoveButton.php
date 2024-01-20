<?php

namespace buttons;

use managers\GameManager;

class MoveButton extends Button
{
    public function execute()
    {
        $from = $_POST['from'];
        $to = $_POST['to'];

        $this->gameManager->move($from, $to);

        header('Location: /');
    }
}