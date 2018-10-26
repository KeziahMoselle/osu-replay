<?php

require('config.php');

$db = new PDO("mysql:host=localhost;dbname=osureplay", $config['DB_USER'], $config['DB_PASSWORD']);

if (isset($_SESSION['auth']) && $_SESSION['auth'] == 1) {
    $req = $db->prepare('SELECT id, username, user_id, avatar_link, replays FROM users WHERE id = ?');
    $req->execute(array($_SESSION['id']));
    $user = $req->fetch();
} else {
    $_SESSION['auth'] = 0;
}

?>
