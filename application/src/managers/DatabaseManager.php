<?php

namespace managers;

use Dotenv\Dotenv;
use base\IDataBaseManager;
use mysqli;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

class DatabaseManager implements IDataBaseManager
{
    private static $instance = null;
    private $connection;

    public function __construct()
    {
        $this->connection = new mysqli(
            $_ENV['DB_HOST'],
            $_ENV['DB_USER'],
            $_ENV['DB_PASSWORD'],
            $_ENV['DB_NAME']
        );
    }

    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new DatabaseManager();
        }
        return self::$instance;
    }

    public static function getConnection()
    {
        return self::getInstance()->connection;
    }

    public static function close()
    {
        if (self::$instance != null) {
            self::$instance->connection->close();
        }
    }

    /**
     * Saves a move to the database.
     * 
     * @param string $from The position of the insect to move OR a piece
     * @param string $to The position to move to
     */
    function saveMove($from, $to)
    {
        $gameId = GameManager::getGameId();
        $lastMove = GameManager::getLastMove();

        $state = GameManager::getState();

        $stmt = $this->prepare('INSERT INTO moves
            (game_id, type, move_from, move_to, previous_id, state)
            VALUES (?, "move", ?, ?, ?, ?)');

        $stmt->bind_param("issis", $gameId, $from, $to, $lastMove, $state);

        GameManager::setLastMove($this->getMoveInsertId());

        $stmt->execute();
    }

    function prepare($query)
    {
        return self::getConnection()->prepare($query);
    }

    /**
     * Gets a move by its id.
     * 
     * @param int $id The id of the move
     * 
     * @return array The move
     */
    function getMoveById($id)
    {
        $stmt = $this->prepare('SELECT * FROM moves WHERE id = ?');

        $stmt->bind_param('i', $id);

        $stmt->execute();

        return $stmt->get_result()->fetch_array();
    }

    /**
     * Gets all moves of the current game.
     * 
     * @return array All moves of the current game
     */
    function getAllMoves()
    {
        $gameId = GameManager::getGameId();

        $stmt = $this->prepare('SELECT * FROM moves WHERE game_id = ?');

        $stmt->bind_param('i', $gameId);

        $stmt->execute();

        return $stmt->get_result();
    }

    /**
     * Deletes all moves of a given game.
     * 
     * @param int $gameId The id of the game
     */
    function deleteAllMovesByGameId($gameId)
    {
        $stmt = $this->prepare('DELETE FROM moves WHERE game_id = ?');

        $stmt->bind_param('i', $gameId);

        $stmt->execute();
    }

    /**
     * Deletes a move by its id.
     * 
     * @param int $id The id of the move
     */
    function deleteMoveById($id)
    {
        $stmt = $this->prepare('DELETE FROM moves WHERE id = ?');

        $stmt->bind_param('i', $id);

        $stmt->execute();
    }

    /**
     * Hack needed because insert_id doesn't seem to be working.
     */
    function getMoveInsertId()
    {
        $gameId = GameManager::getGameId();

        $stmt = $this->prepare('SELECT id FROM moves WHERE game_id = ? ORDER BY id DESC LIMIT 1');

        $stmt->bind_param('i', $gameId);

        $stmt->execute();

        $result = $stmt->get_result()->fetch_array();

        if ($result == null) {
            return 0;
        }

        $id = $result[0];

        return $id == null ? 0 : $id;
    }

    /**
     * Hack needed because insert_id doesn't seem to be working.
     */
    function getGameInsertId()
    {
        $stmt = $this->prepare('SELECT id FROM games ORDER BY id DESC LIMIT 1');

        $stmt->execute();

        $id = $stmt->get_result()->fetch_array()[0];

        return $id == null ? 0 : $id;
    }

    /**
     * Creates a new game.
     */
    function createGame()
    {
        $stmt = $this->prepare('INSERT INTO games VALUES ()');

        $stmt->execute();

        $_SESSION['game_id'] = $this->getGameInsertId();
    }

    /**
     * Gets the last move of the current game.
     * 
     * @return array The last move of the current game
     */
    function getLastMove()
    {
        $gameId = GameManager::getGameId();

        $stmt = $this->prepare('SELECT * from moves WHERE game_id = ? ORDER BY id DESC LIMIT 1');

        $stmt->bind_param('i', $gameId);

        $stmt->execute();

        $result = $stmt->get_result()->fetch_array();

        return $result == null ? null : $result;
    }
}