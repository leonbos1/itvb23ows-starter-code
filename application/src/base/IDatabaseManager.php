<?php

namespace base;

interface IDataBaseManager
{
    function saveMove($from, $to);
    function getMoveById($id);
    function getAllMoves();
    function deleteAllMovesByGameId($gameId);
    function deleteMoveById($id);
    function getMoveInsertId();
    function getGameInsertId();
    function createGame();
    function getLastMove();
}