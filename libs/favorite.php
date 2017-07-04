<?php
session_start();

require 'db.php';

if (isset($_GET['id'],$_GET['token']) AND $_GET['token'] == $_SESSION['token'])
{
  $id = $_GET['id'];
  $user_id = $_SESSION['id'];

  $isfav = $db->prepare('SELECT * FROM favorites WHERE user = ? AND replay = ?');
  $isfav->execute(array($user_id,$id));

  if ($isfav->rowCount() > 0)
  { // Already fav so remove it

    $removeFavorite = $db->prepare('DELETE FROM favorites WHERE user = ? AND replay = ?');
    $removeFavorite->execute(array($user_id,$id));
    header("Location: ../$page");

  }
  else
  { // Not in fav, so fav it

    $addFavorite = $db->prepare('INSERT INTO favorites (user,replay) VALUES(?,?)');
    $addFavorite->execute(array($user_id,$id));
    $page = htmlspecialchars($_GET['ref']);
    header("Location: ../$page");

  }

}
else
{
  die("wtf");
}
?>
