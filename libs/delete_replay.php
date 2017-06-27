<?php
session_start();

$token = htmlspecialchars($_GET['token']);
if ($token = $_SESSION['token'])
{
    require 'db.php';
    $id = htmlspecialchars($_GET['id']);
    $fileName = htmlspecialchars($_GET['fileName']);
    $path = '../uploads/'.$fileName;
    // Delete from DB
    $delete = "DELETE FROM replays WHERE id=$id";
    $db->exec($delete);
    // Delete from server
    unlink($path);
    // -1 On the replay counter
    $req = $db->prepare('SELECT replays FROM users WHERE id=?');
    $req->execute(array($_SESSION['id']));
    $results = $req->fetch();
    $ReplayCounter = $results["replays"] - 1;
    $updateReplayCounter = $db->prepare("UPDATE users SET replays=? WHERE id=?");
    $updateReplayCounter->execute(array($ReplayCounter,$_SESSION['id']));
    // Redir
    header('Location: ../myreplays.php');

}
else
{
    die('Error: token');
}

?>
