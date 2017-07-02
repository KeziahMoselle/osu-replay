<?php
session_start();
$token = htmlspecialchars($_GET['token']);
if ($token = $_SESSION['token'])
{
    session_destroy();
    header('Location: ../index.php');
}
else
{
    die('wtf');
}

?>
