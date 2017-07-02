<?php
session_start();

$token = htmlspecialchars($_GET['token']);
if ($token = $_SESSION['token'])
{
    require 'db.php';
    $id = htmlspecialchars($_GET['id']);
      $reqFile = $db->prepare('SELECT replay_url FROM replays WHERE id=?');
      $reqFile->execute(array($id));
      $reqFileR = $reqFile->fetch();
      $path = '../uploads/'.$reqFileR["replay_url"];
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
    header('Location: ../my-replays.php');

}
else
{
    die('Error: token');
}

?>
