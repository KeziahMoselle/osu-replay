<?php
session_start();

require 'libs/db.php';

?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>osu!replay - Favorites</title>
        <link href="http://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <link type="text/css" rel="stylesheet" href="/assets/css/materialize.min.css"  media="screen,projection"/>
        <link type="text/css" rel="stylesheet" href="/assets/css/style.css"  media="screen,projection"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    </head>
    <body>

        <?php require 'templates/header.php'; ?>

        <nav>
            <div class="nav-wrapper grey darken-3">
                <div class="col s12 center-align">
                    <span class="white-text">
                      Top 50 replays
                    </span>
                </div>
            </div>
        </nav>

        <main>

            <div class="row">



            </div>

        </main>

        <?php require 'templates/footer.php'; ?>
    </body>
</html>
