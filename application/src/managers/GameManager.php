<?php

namespace managers;

use base\IDataBaseManager;
use helpers\RuleHelper;
use helpers\WinHelper;
use mysqli;

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

class GameManager
{
    private IDatabaseManager $db;
    private AiManager $aiManager;
    public static $offsets = [[0, 1], [0, -1], [1, 0], [-1, 0], [-1, 1], [1, -1]];

    function __construct(IDatabaseManager $db = null)
    {
        if ($db == null) {
            $db = new DatabaseManager();
        }

        $this->db = $db;
        $this->aiManager = new AiManager();
        $this->restart();
    }

    function getAiMove()
    {
        $moveNumber = 0;

        $allMoves = $this->db->getAllMoves();

        if ($allMoves) {
            $moveNumber = count($allMoves);
        }

        $results = $this->aiManager->getMove($moveNumber, $this->getHand(), $this->getBoard());

        if (!$results) {
            return;
        }

        if ($results[0] == "play") {
            $this->play($results[1], $results[2]);
        } elseif ($results[0] == "move") {
            $this->move($results[1], $results[2]);
        } elseif ($results[0] == "pass") {
            $this->pass();
        }
    }

    /**
     * Plays a piece on the board.
     * 
     * @param string $piece The piece to play
     * @param string $to The position to play the piece on
     */
    function play($piece, $to)
    {
        if (!RuleHelper::isValidPlay($piece, $to)) {
            return;
        }

        $player = $this->getPlayer();

        $board = $this->getBoard();

        self::setError();

        $board[$to] = [[$player, $piece]];

        self::setBoard($board);

        $_SESSION['hand'][$player][$piece]--;

        $this->setLastMove($this->db->getMoveInsertId());

        $this->db->saveMove($piece, $to);

        $this->swapPlayers();

        if (WinHelper::isGameOver()) {
            $this->handleGameOver();
        }
    }

    /**
     * Moves a piece on the board.
     * 
     * @param string $from The position of the insect to move
     * @param string $to The position to move to
     */
    function move($from, $to)
    {
        if (!RuleHelper::isValidMove($from, $to)) {
            return;
        }

        $board = $this->getBoard();

        $tile = array_pop($board[$from]);

        if (count($board[$from]) == 0) {
            unset($board[$from]);
        }

        if (!isset($board[$to])) {
            $board[$to] = [$tile];
        } else {
            $stackedTiles = count($board[$to]);

            $board[$to][$stackedTiles + 1] = $tile;
        }

        self::setError();
        self::setBoard($board);

        $this->setLastMove($this->db->getMoveInsertId());
        $this->db->saveMove($from, $to);

        $this->swapPlayers();

        if (WinHelper::isGameOver()) {
            $this->handleGameOver();
        }
    }

    function handleGameOver()
    {
        $isTie = WinHelper::gameTied();

        if ($isTie) {
            self::setError("Game is tied");
        } else {
            $winner = WinHelper::getWinner();
            if ($winner === 0) {
                self::setError("White player won");
            } else {
                self::setError("Black player won");
            }
        }
    }

    /**
     * Restarts the game.
     */
    function restart()
    {
        $this->setBoard([]);
        $this->setHand($this->getStarterHand());
        $this->setPlayer(0);

        unset($_SESSION['last_move']);
        unset($_SESSION['error']);

        $this->db->createGame();
        $_SESSION['game_id'] = $this->db->getGameInsertId();
    }

    /**
     * Undoes the last move.
     */
    function undo()
    {
        $lastMove = $this->db->getLastMove();

        if (!$lastMove) {
            $this->restart();

            return;
        }

        $this->db->deleteMoveById($lastMove[0]);

        $prevMoveId = $lastMove[5];

        $prevMove = $this->db->getMoveById($prevMoveId);

        if (!$prevMove) {
            $this->restart();

            return;
        }

        $this->setState($prevMove[6]);

        $this->setLastMove($prevMoveId);

        $this->swapPlayers();
    }

    function pass()
    {
        if (!RuleHelper::hasToPass()) {
            self::setError("Player can still do a move");
            return;
        }

        $this->swapPlayers();
        GameManager::setError();
        $this->db->saveMove(null, null);
    }


    /**
     * Gets the board.
     * 
     * @return array The board
     */
    public static function getBoard()
    {
        if (!isset($_SESSION['board'])) {
            $_SESSION['board'] = [];
            return $_SESSION['board'];
        }
        return $_SESSION['board'];
    }

    /**
     * Sets the board.
     * 
     * @param array $board The board to set
     */
    public static function setBoard(array $board)
    {
        $_SESSION['board'] = $board;
    }

    /**
     * Gets the hand. When no player is specified, the hand of both players is returned.
     * 
     * @param int $player The player to get the hand of, optional
     * 
     * @return array The hand
     */
    public static function getHand(int $player = null)
    {
        if ($player === null) {
            return $_SESSION['hand'] ?? null;
        }

        $hand = $_SESSION['hand'][$player];

        if (!$hand) {
            $hand = self::getStarterHand()[$player];
            self::setHand($hand);
        }

        return $hand;
    }

    /**
     * Sets the hand.
     * 
     * @param array $hand The hand to set
     */
    public static function setHand(array $hand)
    {
        $_SESSION['hand'] = $hand;
    }

    /**
     * Gets the state.
     */
    public static function getState()
    {
        return serialize([$_SESSION['hand'], $_SESSION['board'], $_SESSION['player']]);
    }

    /**
     * Sets the state.
     * 
     * @param string $state The state to set
     */
    public static function setState($state)
    {
        list($hand, $board, $player) = unserialize($state);

        self::setHand($hand);
        self::setBoard($board);
        self::setPlayer($player);
    }

    /**
     * Unsets the state.
     */
    public static function unsetState()
    {
        self::setBoard([]);
        self::setHand(GameManager::getStarterHand());
        self::setPlayer(0);
    }

    /**
     * Gets the last move.
     */
    public static function getLastMove()
    {
        return $_SESSION['last_move'] ?? null;
    }

    /**
     * Sets the last move.
     * 
     * @param int $last_move The last move to set
     */
    public static function setLastMove(int $last_move)
    {
        $_SESSION['last_move'] = $last_move;
    }

    /**
     * Gets the game id.
     */
    public static function getGameId()
    {
        return $_SESSION['game_id'] ?? null;
    }

    /**
     * Sets the game id.
     * 
     * @param int $game_id The game id to set
     */
    public static function setGameId(int $game_id)
    {
        $_SESSION['game_id'] = $game_id;
    }

    /**
     * Gets the player.
     */
    public static function getPlayer()
    {
        if (!isset($_SESSION['player'])) {
            $_SESSION['player'] = 0;
            return $_SESSION['player'];
        }
        return $_SESSION['player'];
    }

    /**
     * Sets the player.
     * 
     * @param int $player The player to set
     */
    public static function setPlayer(int $player)
    {
        $_SESSION['player'] = $player;
    }

    /**
     * Swaps the players.
     */
    function swapPlayers()
    {
        $_SESSION['player'] = 1 - $_SESSION['player'];
    }

    /**
     * Gets the error.
     */
    public static function getError()
    {
        return $_SESSION['error'] ?? null;
    }

    /**
     * Sets the error.
     * 
     * @param string $error The error to set
     */
    public static function setError(string $error = null)
    {
        $_SESSION['error'] = $error;
    }

    /**
     * Gets the starter hand.
     * 
     * @return array The starter hand
     */
    public static function getStarterHand(): array
    {
        return [
            0 => [
                "Q" => 1,
                "B" => 2,
                "S" => 2,
                "A" => 3,
                "G" => 3
            ],
            1 => [
                "Q" => 1,
                "B" => 2,
                "S" => 2,
                "A" => 3,
                "G" => 3
            ]
        ];
    }
}