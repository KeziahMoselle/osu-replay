<?php

$db = new PDO("mysql:host=localhost;dbname=osureplay_3xij", "osureplay_3xij", "starchixx");

if (isset($_SESSION['auth']) && $_SESSION['auth'] == 1)
{
    $req = $db->prepare('SELECT * FROM users WHERE id = ?');
    $req->execute(array($_SESSION['id']));
    $user = $req->fetch();
}

?>
