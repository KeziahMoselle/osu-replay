<?php
session_start();

require 'db.php';

if (isset($_GET['id'],$_GET['token']) AND $_GET['token'] == $_SESSION['token'])
{
  $id = $_GET['id'];
  $user_id = $_SESSION['id'];
  die("ok!");
}
else
{
  die("wtf");
}

?>
