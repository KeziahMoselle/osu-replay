<?php
session_start();

require 'db.php';

if (isset($_POST['search']))
{ // Search

  $search = htmlspecialchars($_POST['search']);
  $sql = "SELECT * FROM `replays` WHERE CONCAT(`artist`, `title`, `version`, `creator`, `player`) LIKE '%".$search."%'";
  $query = $db->prepare($sql);
  $query->execute();

  echo json_encode($replays = $query->fetch());



}
else
{

  $query = $db->prepare('SELECT * FROM replays WHERE visibility = ? ORDER BY id DESC');
  $query->execute(array("public"));

}

?>
