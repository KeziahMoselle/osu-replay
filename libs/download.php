<?php
session_start();

if (isset($_GET['id']))
{
  require 'db.php';
  $id = htmlspecialchars($_GET['id']);

  // FETCH DL COUNT OF THE REPLAY
  $req = $db->prepare('SELECT visibility,replay_url,dl_count,uploader FROM replays WHERE id = ?');
  $req->execute(array($id));
  $replay = $req->fetch();

  $visibility = $replay['visibility'];
  $file = $replay['replay_url'];
  $uploader = $replay['uploader'];

  switch ($visibility)
  {
    case 'public':
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
    break;

    case 'private':
      if ($uploader == $_SESSION['username'])
      {
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
        die('You can\'t download this replay, because it\'s private');
      }
    break;
  }

}
else
{
  die('wtf');
}

?>
