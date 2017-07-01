<?php
session_start();

require 'libs/db.php';

// EDIT
if (isset($_GET['edit'],$_GET['token']) AND $_GET['token'] == $_SESSION['token'])
{
    // REPLAY DATA
    $id = htmlspecialchars($_GET['edit']);
    $req = $db->prepare('SELECT * FROM replays WHERE id = ?');
    $req->execute(array($id));
    $replay = $req->fetch();

    // UPDATE DATA
    if (isset($_POST['edit']))
    {
        // POST DATA
        $beatmap_url = htmlspecialchars($_POST['beatmap_url']);
        $player_username = htmlspecialchars($_POST['player_username']);
        $youtube_url = htmlspecialchars($_POST['youtube_url']);
        if (isset($_POST['visibility']))
        {
            $visibility = "private";
        }
        else
        {
            $visibility = "public";
        }

        // OSU API
        require 'libs/osu_api.php';

        // Beatmap
        $beatmap_url = htmlspecialchars($_POST['beatmap_url']); // POST DATA
        preg_match('^https:\/\/osu\.ppy\.sh\/b\/(\d*)^', $beatmap_url, $matches); // REGEX
        $beatmap_id = $matches[1]; // FETCH ID
        $beatmap = get_beatmaps($key,$beatmap_id); // FETCH BM INFO
        // Beatmap info
        $artist = $beatmap[0]["artist"];
        $title = $beatmap[0]["title"];
        $version = $beatmap[0]["version"];
        $creator = $beatmap[0]["creator"];
        $mode = $beatmap[0]["mode"];
        $difficultyrating = round($beatmap[0]["difficultyrating"], 2);
        $beatmapset_id = $beatmap[0]["beatmapset_id"];

        // Player
        $player_username = htmlspecialchars($_POST['player_username']); // POST DATA
        $get_user = get_user($key,$player_username); // FETCH USER INFO
        $player = $get_user[0]["username"];
        $player_id = $get_user[0]["user_id"];
        $player_url = 'https://osu.ppy.sh/u/'.$player_id;
        $player_rank = $get_user[0]["pp_rank"];
        $country = $get_user[0]["country"];

        // Infos
        $uploader = $_SESSION['username'];

        // UPDATE
        $update = $db->prepare('UPDATE replays SET visibility=?,artist=?,title=?,version=?,creator=?,mode=?,difficultyrating=?,beatmap_id=?,beatmap_url=?,beatmapset_id=?,player=?,player_rank=?,country=?,player_id=?,player_url=?,uploader=?, youtube_url=? WHERE id=?');
        $update->execute(array($visibility, $artist, $title, $version, $creator, $mode, $difficultyrating, $beatmap_id, $beatmap_url, $beatmapset_id, $player, $player_rank, $country, $player_id, $player_url, $uploader, $youtube_url,$id));
        $notif = "Replay updated";
        // REFRESH DATA
        $req = $db->prepare('SELECT * FROM replays WHERE id = ?');
        $req->execute(array($id));
        $replay = $req->fetch();
        header('Location: myreplays.php');

    }

}
else
{
  // REPLAY LIST
  $replays = $db->prepare('SELECT * FROM replays WHERE uploader = ? ORDER BY id DESC');
  $replays->execute(array($_SESSION['username']));
}

?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>osu!replay - My replays</title>
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
                      <?php
                        if (!isset($_GET['edit']))
                        {
                          if ($replays->rowCount() == 0) {echo 'Any replay found.';}
                          else {echo 'You have ' .$user['replays']. ' replays.';}
                        }
                        else
                        {
                          if (isset($notif))
                          {
                            echo $notif;
                          }
                          else {
                            echo 'Editing : ' .$replay['replay_url'];
                          }
                        }
                       ?>

                    </span>
                </div>
            </div>
        </nav>

        <main>

            <div class="row">

              <?php if (isset($_GET['edit'])): ?>
                <div class="col s12 m10 offset-m1 l6 offset-l3">

                    <div class="card white">
                        <div class="card-content">
                            <h1 class="center-align">Edit a replay</h1>
                            <form class="row" action="myreplays.php?edit=<?=$replay['id']?>&token=<?=$_SESSION['token']?>" method="post">
                                <div class="input-field col s12 l6">
                                  <input name="beatmap_url" id="beatmap_url" type="text" placeholder="https://osu.ppy.sh/b/00000" value="<?=$replay['beatmap_url']?>">
                                  <label for="beatmap_url">Beatmap ID</label>
                                </div>
                                <div class="input-field col s12 l6">
                                  <input name="player_username" id="player" type="text" placeholder="https://osu.ppy.sh/u/00000" value="<?=$replay['player']?>">
                                  <label for="player">Player</label>
                                </div>
                                <div class="input-field col s12 l6 offset-l3">
                                  <input name="youtube_url" id="youtube_url" type="text" placeholder="https://www.youtube.com/watch?v=abcdef0123" value="<?=$replay['youtube_url']?>">
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



                  <?php if (!isset($_GET['edit'])): ?>
                    <?php if ($replays->rowCount() > 0): ?>
                      <div class="col m10 offset-m1">
                        <?php while($replay = $replays->fetch()) { ?>
                            <div class="col s12 m6 l4">
                              <div class="card grey lighten-3">
                                <div class="card-image waves-effect waves-block waves-light">
                                    <div class="dl_count"><span><?=$replay['dl_count']?></span><i class="material-icons">file_download</i></div>
                                    <img class="activator" src="https://assets.ppy.sh//beatmaps/<?=$replay["beatmapset_id"]?>/covers/card.jpg">
                                    <span class="card-title truncate"><?=$replay["title"]?><br/><?=$replay["artist"]?></span>
                                </div>
                                <div class="card-content center-align">
                                  <span class="card-title activator grey-text text-darken-4">Played by <?=$replay["player"]?> (#<?=$replay['player_rank']?>)<br/>On [<?=$replay["version"]?>] (<?=$replay["difficultyrating"]?>*)<i class="material-icons right">more_vert</i></span>
                                </div>
                                <div class="card-reveal">
                                  <span class="card-title grey-text text-darken-4">Card Title<i class="material-icons right">close</i></span>
                                  <p><a href="myreplays.php?edit=<?=$replay['id']?>&token=<?=$_SESSION['token']?>" class="btn waves-effect waves grey">Edit</a></p>
                                  <a href="libs/delete_replay.php?id=<?=$replay['id']?>&token=<?=$_SESSION['token']?>" class="btn-floating floating-left halfway-fab waves-effect waves-light red"><i class="material-icons">delete</i></a>
                                  <a href="libs/download.php?id=<?=$replay['id']?>" class="btn-floating halfway-fab waves-effect waves-light deep-purple accent-2"><i class="material-icons">play_for_work</i></a>
                                </div>
                              </div>
                            </div>
                        <?php } ?>
                        </div>
                    <?php endif; ?>
                  <?php endif; ?>

            </div>

        </main>

        <?php require 'templates/footer.php'; ?>
    </body>
</html>
