<?php

$db = new PDO("mysql:host=localhost;dbname=<dbName>", "<dbName>", "<pass>");

if (isset($_SESSION['auth']) && $_SESSION['auth'] == 1)
{
    $req = $db->prepare('SELECT * FROM users WHERE id = ?');
    $req->execute(array($_SESSION['id']));
    $user = $req->fetch();
}

?>
