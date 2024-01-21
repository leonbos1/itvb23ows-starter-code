<?php

require_once __DIR__ . '/vendor/autoload.php';

use managers\GameManager;
use buttons\PassButton;
use buttons\PlayButton;
use buttons\RestartButton;
use buttons\UndoButton;
use buttons\MoveButton;
use buttons\AiButton;

$gameManager = new GameManager();

if (!isset($_POST['action'])) {
    exit("No action specified");
}

$action = $_POST['action'];

$mappings = [
    'play' => new PlayButton($gameManager),
    'pass' => new PassButton($gameManager),
    'restart' => new RestartButton($gameManager),
    'undo' => new UndoButton($gameManager),
    'move' => new MoveButton($gameManager),
    'ai' => new AiButton($gameManager),
];

isset($mappings[$action]) ? $mappings[$action]->execute() : exit("Invalid action");
