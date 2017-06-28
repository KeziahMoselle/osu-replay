<?php
session_start();

if (isset($_GET['replay_url']))
{
  require 'db.php';
  $replay_url = htmlspecialchars($_GET['replay_url']);
  $file = htmlspecialchars($_GET['replay_url']);

  // FETCH DL COUNT OF THE REPLAY
  $req = $db->prepare('SELECT dl_count FROM replays WHERE replay_url = ?');
  $req->execute(array($replay_url));
  $replay = $req->fetch();

  // SET THE NEW dl_count
  $dl_count = $replay['dl_count'] + 1;

  // UPDATE dl_count OF THE REPLAY
  $update = $db->prepare('UPDATE replays SET dl_count = ? WHERE replay_url = ?');
  $update->execute(array($dl_count,$replay_url));

  // DOWNLOAD THE .OSR
  header('Content-Type: application/octet-stream');
  header('Content-Transfer-Encoding: Binary');
  header('Content-disposition: attachment; filename="' .$file. '"');
  echo readfile('../uploads/'.$file);

}
else
{
  die('Error');
}

?>
