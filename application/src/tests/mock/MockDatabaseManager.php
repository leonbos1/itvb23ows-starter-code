<?php

namespace mock;

use base\IDataBaseManager;
use managers\GameManager;

class MockDatabaseManager implements IDataBaseManager
{
    private array $gamesTable;
    private array $movesTable;
    private int $lastMoveId;

    public function __construct()
    {
        $this->gamesTable = [];
        $this->movesTable = [];
        $this->lastMoveId = 0;
    }

    function saveMove($from, $to)
    {
        $newId = count($this->movesTable) + 1;
        $this->lastMoveId = $newId;

        $this->movesTable[] = [$newId, GameManager::getGameId(), "move", $from, $to, GameManager::getLastMove(), GameManager::getState()];

        GameManager::setLastMove($newId);
    }

    function getMoveById($id)
    {
        return $this->movesTable[$id - 1];
    }

    function getAllMoves()
    {
        return $this->movesTable;
    }

    function unsetState()
    {
        GameManager::setBoard([]);
        GameManager::setHand(GameManager::getStarterHand());
        GameManager::setPlayer(0);
    }

    function deleteAllMovesByGameId($gameId)
    {
        $this->movesTable = [];
    }

    function deleteMoveById($id)
    {
        unset($this->movesTable[$id - 1]);
    }

    function getMoveInsertId()
    {
        return $this->lastMoveId;
    }

    function getGameInsertId()
    {
        return count($this->gamesTable) + 1;
    }

    function createGame()
    {
        $this->gamesTable[] = [count($this->gamesTable) + 1];

        $_SESSION['game_id'] = $this->getGameInsertId();
    }

    function getLastMove()
    {
        $gameId = GameManager::getGameId();

        $lastMove = null;

        $movesFromGame = [];

        foreach ($this->movesTable as $move) {
            if ($move[1] == $gameId) {
                $movesFromGame[] = $move;
            }
        }

        foreach ($movesFromGame as $move) {
            if ($move[0] > $lastMove[0]) {
                $lastMove = $move;
            }
        }

        return $lastMove;
    }
}