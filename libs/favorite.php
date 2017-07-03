<?php
session_start();

require 'db.php';

if (isset($_GET['id']))
{
  $id = $_GET['id'];
  $user_id = $_SESSION['id'];

  $req = $db->query("$sql");
}

?>
