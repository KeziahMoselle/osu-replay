<?php
session_start();

$token = htmlspecialchars($_GET['token']);
if ($token = $_SESSION['token'])
{
    require 'db.php';

    $id = htmlspecialchars($_GET['id']);

    $req = $db->prepare('SELECT * FROM replays WHERE id=?');
    $req->execute(array($id));
    $replay = $req->fetch();

    if ($replay["uploader"] == $_SESSION["username"])
    {
      $path = '../uploads/'.$reqFileR["replay_url"];
      // Delete from DB
      $delete = "DELETE FROM replays WHERE id=$id";
      $db->exec($delete);
      // Delete from server
      unlink($path);
      // -1 On the replay counter
      $reqReplay = $db->prepare('SELECT replays FROM users WHERE id=?');
      $reqReplay->execute(array($_SESSION['id']));
      $results = $reqReplay->fetch();
      $ReplayCounter = $results["replays"] - 1;
      $updateReplayCounter = $db->prepare("UPDATE users SET replays=? WHERE id=?");
      $updateReplayCounter->execute(array($ReplayCounter,$_SESSION['id']));
      // Redir
      header('Location: ../my-replays.php');
    }
    else
    {
      die('wtf');
    }
}
else
{
    die('Error: token');
}

?>
