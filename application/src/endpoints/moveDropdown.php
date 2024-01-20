<?php

namespace endpoints;

require_once __DIR__ . '/../vendor/autoload.php';

use managers\GameManager;
use helpers\InsectHelper;

$board = GameManager::getBoard();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    exit(0);
}

if (isset($_POST['from'])) {
    $from = $_POST['from'];
    if ($from === '' || $from === null) {
        echo json_encode([]);
        exit(0);
    }

    $allowed_moves = getPossibleMovesInsect($board, $from);
    echo json_encode($allowed_moves);
    exit(0);

}


/**
 * Returns an array of possible moves for the insect at the given position
 * @param array $board The current board state
 * @param string $from The position of the insect to move
 * @return array An array of possible moves
 */
function getPossibleMovesInsect($board, $from)
{
    $hand = GameManager::getHand(GameManager::getPlayer());

    $queenIsPlaced = $hand['Q'] === 0;

    if (!$queenIsPlaced) return [];

    $insect_type = InsectHelper::getInsectInstance($board[$from][0][1]);

    $allowed_moves = $insect_type->getPossibleMoves($board, $from);

    return $allowed_moves;
}