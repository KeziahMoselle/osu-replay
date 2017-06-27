<?php
session_start();

require 'libs/db.php';

if (isset($_POST['upload']))
{
    $GET_token = htmlspecialchars($_GET['token']);
    if (isset($_GET['token']) AND $GET_token == $_SESSION['token'])
    {
        if (isset($_POST['beatmap_url']))
        {
            if (isset($_POST['player_url']))
            {
                if (isset($_POST['visibility']))
                {
                    $visibility = "private";
                }
                else
                {
                    $visibility = "public";
                }

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
                $player_url = htmlspecialchars($_POST['player_url']); // POST DATA
                preg_match('^https:\/\/osu\.ppy\.sh\/u\/(\d*)^', $player_url, $matches); // REGEX
                $player_id = $matches[1]; //FETCH ID
                $get_user = get_user($key,$player_id); // FETCH USER INFO
                $player = $get_user[0]["username"];
                $player_rank = $get_user[0]["pp_rank"];
                $country = $get_user[0]["country"];
                // Infos
                $uploader = $_SESSION['username'];


                // Traitement du fichier
                $file = $_FILES['file'];
                $fileName = $_FILES['file']['name'];
                $fileTmpName = $_FILES['file']['tmp_name'];
                $fileSize = $_FILES['file']['size'];
                $fileError = $_FILES['file']['error'];
                $fileType = $_FILES['file']['type'];

                $fileExt = explode('.', $fileName);
                $fileActualExt = strtolower(end($fileExt));


                if ($fileActualExt == 'osr')
                {
                    if ($fileError === 0)
                    {
                        if ($fileSize < 10000000)
                        {
                            $req = $db->prepare('SELECT replays FROM users WHERE id=?');
                            $req->execute(array($_SESSION['id']));
                            $results = $req->fetch();
                            $ReplayCounter = $results["replays"] + 1;
                            $updateReplayCounter = $db->prepare("UPDATE users SET replays=? WHERE id=?");
                            $updateReplayCounter->execute(array($ReplayCounter,$_SESSION['id']));

                            $fileNameNew = "$player : $artist - $title [$version] ($uploader($ReplayCounter)).$fileActualExt";
                            $fileDestination = 'uploads/'.$fileNameNew;
                            move_uploaded_file($fileTmpName, $fileDestination);
                            // Insert DB
                            $newReplay = $db->prepare("INSERT INTO replays(visibility,replay_url,artist,title,version,creator,mode,difficultyrating,beatmap_id,beatmapset_id,player,player_rank,country,player_id,uploader) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
                            $newReplay->execute(array($visibility,$fileNameNew,$artist,$title,$version,$creator,$mode,$difficultyrating,$beatmap_id,$beatmapset_id,$player,$player_rank,$country,$player_id,$uploader));

                            $notif = "Replay uploaded.";
                        }
                        else
                        {
                            $notif = "[Error] Your file is too big.";
                        }
                    }
                    else
                    {
                        $notif = "[Error] There was an error uploading your file.";
                    }
                }
                else
                {
                    $notif = "[Error] .osr only";
                }


            }
            else
            {
                $notif = "[Error] Player URL is empty.";
            }
        }
        else
        {
            $notif = "[Error] Beatmap URL is empty.<br/>";
            if (isset($_POST['player_url']))
            {
                $notif .= "Player URL is empty.";
            }
        }
    }
    else
    {
        die('Token error.');
    }
}

?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>osu!replay - Upload</title>
        <link href="http://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <link type="text/css" rel="stylesheet" href="/assets/css/materialize.min.css"  media="screen,projection"/>
        <link type="text/css" rel="stylesheet" href="/assets/css/style.css"  media="screen,projection"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    </head>
    <body>

        <?php require 'templates/header.php'; ?>

        <main>

            <div class="row">

                <div class="col s12 m10 offset-m1 l6 offset-l3">

                    <?php if(isset($notif)): ?>
                        <div class="card-panel white center-align">
                            <span class="black-text"><?= $notif ?></span>
                        </div>
                    <?php endif ?>

                    <div class="card white">
                        <div class="card-content">
                            <h1 class="center-align">Upload</h1>
                            <form class="row" action="upload.php?token=<?=$_SESSION['token']?>" method="post" enctype="multipart/form-data">
                                <div class="input-field col s12 l6">
                                  <input name="beatmap_url" id="beatmap_id" type="text" placeholder="https://osu.ppy.sh/b/00000">
                                  <label for="beatmap_id">Beatmap ID</label>
                                </div>
                                <div class="input-field col s12 l6">
                                  <input name="player_url" id="player" type="text" placeholder="https://osu.ppy.sh/u/00000">
                                  <label for="player">Player</label>
                                </div>
                                <div class="file-field input-field col s12">
                                  <div class="btn waves-effect waves-light deep-purple accent-2">
                                    <span>File</span>
                                    <input name="file" type="file" required>
                                  </div>
                                  <div class="file-path-wrapper">
                                    <input class="file-path validate" type="text" placeholder="Upload the .osr file" required>
                                  </div>
                                </div>
                                <div class="center">
                                    <div class="switch">
                                        <label>
                                            Public
                                            <input name="visibility" type="checkbox">
                                            <span class="lever"></span>
                                            Private
                                        </label>
                                    </div>
                                </div>
                        </div>
                        <div class="card-action center">
                            <button name="upload" type="submit" class="btn waves-effect waves deep-purple accent-2">Upload</button>
                            </form>
                        </div>
                    </div>

                </div>

            </div>

        </main>

        <?php require 'templates/footer.php'; ?>
    </body>
</html>
