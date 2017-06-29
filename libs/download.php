<?php
session_start();

if (isset($_GET['id']))
{
  require 'db.php';
  $id = htmlspecialchars($_GET['id']);

  // FETCH DL COUNT OF THE REPLAY
  $req = $db->prepare('SELECT replay_url,dl_count FROM replays WHERE id = ?');
  $req->execute(array($id));
  $replay = $req->fetch();

  $file = $replay['replay_url'];

  // SET THE NEW dl_count
  $dl_count = $replay['dl_count'] + 1;

  // UPDATE dl_count OF THE REPLAY
  $update = $db->prepare('UPDATE replays SET dl_count = ? WHERE id = ?');
  $update->execute(array($dl_count,$id));

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
