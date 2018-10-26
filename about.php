<?php
session_start();
$title = "About";
$page = "about.php";

?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>osu!replay - <?=$title?></title>
        <link href="http://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <link type="text/css" rel="stylesheet" href="/assets/css/materialize.min.css"  media="screen,projection"/>
        <link type="text/css" rel="stylesheet" href="/assets/css/style.css"  media="screen,projection"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    </head>
    <body>

        <?php require 'templates/header.php'; ?>


        <main>

            <div class="row">

                <div class="col m8 offset-m2">

                    <div class="card white">
                        <div class="card-content">
                            <h1 class="center-align">About</h1>
                            <p>Sorry, no content here. ¯\_(ツ)_/¯</p>
                        </div>
                    </div>

                </div>

            </div>

        </main>

        <?php require 'templates/footer.php'; ?>
    </body>
</html>
