<?php

function get_state() {
    return serialize([$_SESSION['hand'], $_SESSION['board'], $_SESSION['player']]);
}

function set_state($state) {
    list($a, $b, $c) = unserialize($state);
    $_SESSION['hand'] = $a;
    $_SESSION['board'] = $b;
    $_SESSION['player'] = $c;
}
// development
return new mysqli('localhost', 'root', 'mysecretpassword', 'hive');
// containerized
//return new mysqli('hive-database', 'root', 'mysecretpassword', 'hive');
?>