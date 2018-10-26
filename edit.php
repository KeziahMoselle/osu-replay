<?php
session_start();
$title = "Edit";
$page = "edit.php";

require 'libs/db.php';

if (isset($_GET['id'],$_GET['token']) AND $_GET['token'] == $_SESSION['token'])
{
    // FILL REPLAY INPUTS
    $id = htmlspecialchars($_GET['id']);
    $req = $db->prepare('SELECT * FROM replays WHERE id = ?');
    $req->execute(array($id));
    $replay = $req->fetch();

    if (isset($_POST['edit']))
    {
        if (isset($_POST['visibility']))
        {
            $visibility = "private";
        }
        else
        {
            $visibility = "public";
        }
        $youtube_url = htmlspecialchars($_POST['youtube_url']);

        //die(var_dump($visibility, $youtube_url, $id));
        // UPDATE
        $update = $db->prepare('UPDATE replays SET visibility = ?, youtube_url = ? WHERE id = ?');
        $update->execute(array($visibility, $youtube_url ,$id));
        // Redirect
        header("Location: ../$page");
    }
}
else
{
  header('Location: my-replays.php');
}

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

              <?php if (isset($_GET['id'])): ?>
                <div class="col s12 m10 offset-m1 l6 offset-l3">
                    <div class="card white">
                        <div class="card-content">
                            <h1 class="center-align">Edit a replay</h1>
                            <form class="row" action="edit.php?id=<?=$replay['id']?>&token=<?=$_SESSION['token']?>" method="post">
                                <div class="input-field col s12 l8 offset-l2">
                                  <input name="beatmap_url" id="beatmap_url" type="text" placeholder="https://osu.ppy.sh/beatmapsets/0000#mode/0000" value="<?=$replay['beatmap_url']?>">
                                  <label for="beatmap_url">Beatmap ID</label>
                                </div>
                                <div class="input-field col s12 l8 offset-l2">
                                  <input name="player_username" id="player" type="text" placeholder="https://osu.ppy.sh/users/00000" value="<?=$replay['player']?>">
                                  <label for="player">Player</label>
                                </div>
                                <div class="input-field col s12 l8 offset-l2">
                                  <input name="youtube_url" id="youtube_url" type="text" placeholder="https://www.youtube.com/watch?v=0000" value="<?=$replay['youtube_url']?>">
                                  <label for="youtube_url">Youtube URL</label>
                                </div>
                                <div class="center col s12">
                                    <div class="switch">
                                        <label>
                                            Public
                                            <input name="visibility" type="checkbox" <?php if($replay['visibility'] == 'private') {echo "checked";}?>>
                                            <span class="lever"></span>
                                            Private
                                        </label>
                                    </div>
                                </div>
                        </div>
                        <div class="card-action center">
                            <button name="edit" type="submit" class="btn waves-effect waves deep-purple accent-2">Edit</button>
                            </form>
                        </div>
                    </div>
                  </div>
              <?php endif; ?>

            </div>

        </main>

        <?php require 'templates/footer.php'; ?>
    </body>
</html>
