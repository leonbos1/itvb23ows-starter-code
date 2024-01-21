<?php

require_once 'vendor/autoload.php';

session_start();

use managers\GameManager;
use managers\DatabaseManager;
use helpers\MoveHelper;
use helpers\RuleHelper;

$board = GameManager::getBoard();
$player = GameManager::getPlayer();
$hand = GameManager::getHand();

$gameManager = new GameManager();

if (!isset($board)) {
    $gameManager->restart();
}

$to = [];
foreach (GameManager::$offsets as $pq) {
    foreach (array_keys($board) as $pos) {
        $pq2 = explode(',', $pos);
        $to[] = ($pq[0] + $pq2[0]) . ',' . ($pq[1] + $pq2[1]);
    }
}
$to = array_unique($to);
if (!count($to))
    $to[] = '0,0';

?>
<!DOCTYPE html>
<html>

<head>
    <title>Hive</title>
    <link rel="stylesheet" href="styles/index.css">
    <script src="scripts/index.js"></script>
</head>

<body>
    <div class="board">
        <?php
        $min_p = 1000;
        $min_q = 1000;
        foreach ($board as $pos => $tile) {
            $pq = explode(',', $pos);
            if ($pq[0] < $min_p)
                $min_p = $pq[0];
            if ($pq[1] < $min_q)
                $min_q = $pq[1];
        }
        foreach (array_filter($board) as $pos => $tile) {
            $pq = explode(',', $pos);
            $pq[0];
            $pq[1];
            $h = count($tile);
            echo '<div class="tile player';
            echo $tile[$h - 1][0];
            if ($h > 1)
                echo ' stacked';
            echo '" style="left: ';
            echo ($pq[0] - $min_p) * 4 + ($pq[1] - $min_q) * 2;
            echo 'em; top: ';
            echo ($pq[1] - $min_q) * 4;
            echo "em;\">($pq[0],$pq[1])<span>";
            echo $tile[$h - 1][1];
            echo '</span></div>';
        }
        ?>
    </div>
    <div class="hand">
        White:
        <?php
        foreach ($hand[0] as $tile => $ct) {
            for ($i = 0; $i < $ct; $i++) {
                echo '<div class="tile player0"><span>' . $tile . "</span></div> ";
            }
        }
        ?>
    </div>
    <div class="hand">
        Black:
        <?php
        foreach ($hand[1] as $tile => $ct) {
            for ($i = 0; $i < $ct; $i++) {
                echo '<div class="tile player1"><span>' . $tile . "</span></div> ";
            }
        }
        ?>
    </div>
    <div class="turn">
        Turn:
        <?php if ($player == 0)
            echo "White";
        else
            echo "Black"; ?>
    </div>
    <form method="post" action="action.php">
        <p>New Insect</p>
        <label for="piece">Piece:</label>
        <select name="piece">
            <?php
            foreach ($hand[$player] as $tile => $ct) {
                if ($ct == 0)
                    continue;
                echo "<option value=\"$tile\">$tile</option>";
            }
            ?>
        </select>
        <label for="to">To:</label>
        <select name="to">
            <?php
            foreach (MoveHelper::getPossiblePlacements($board, $player) as $pos) {
                echo "<option value=\"$pos\">$pos</option>";
            }
            ?>
        </select>
        <input type="hidden" name="action" value="play">
        <input type="submit" value="Play">
    </form>
    <form method="post" action="action.php">
        <p>Move</p>
        <label for="from">From:</label>
        <select name="from" id="from-dropdown">
            <?php
            foreach (array_keys($board) as $pos) {
                if (RuleHelper::tileInHand($board, $player, $pos)) {
                    echo "<option value=\"$pos\">$pos</option>";
                }
            }
            ?>
        </select>
        <label for="to">To:</label>
        <select name="to" id="to-dropdown">
            <?php
            foreach ($to as $pos) {
                if (isset($board[$pos]))
                    continue;
                if (RuleHelper::hasNeighbour($pos, $board) && MoveHelper::neighboursAreSameColor($player, $pos, $board)) {
                    echo "<option value=\"$pos\">$pos</option>";
                }
            }
            ?>
        </select>
        <input type="hidden" name="action" value="move">
        <input type="submit" id="move-submit-btn" value="Move">
    </form>
    <form method="post" action="action.php">
        <input type="hidden" name="action" value="pass">
        <input type="submit" value="Pass">
    </form>
    <form method="post" action="action.php">
        <input type="hidden" name="action" value="ai">
        <input type="submit" value="Ai Move">
    </form>
    <form method="post" action="action.php">
        <input type="hidden" name="action" value="restart">
        <input type="submit" value="Restart">
    </form>
    <strong>
        <?php
        $error = $gameManager->getError();
        if (isset($error))
            echo $error;
        $gameManager->setError();
        ?>
    </strong>
    <ol>
        <?php
        $db = DatabaseManager::getInstance();

        $result = $db->getAllMoves();

        while ($row = $result->fetch_array()) {
            echo '<li>' . $row[2] . ' ' . $row[3] . ' ' . $row[4] . '</li>';
        }
        ?>
    </ol>
    <form method="post" action="action.php">
        <input type="hidden" name="action" value="undo">
        <input type="submit" value="Undo">
    </form>
</body>

</html>