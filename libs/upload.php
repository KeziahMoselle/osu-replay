<?php
session_start();

require 'db.php';
require 'osu_api.php';

if (isset($_POST['upload']))
{
  if (isset($_POST['beatmap_url'],$_POST['player_username'],$_FILES['file']))
  {
    if (isset($_POST['visibility']))
    {
        $visibility = "private";
    }
    else
    {
        $visibility = "public";
    }

    // Beatmap
      $beatmap_url = htmlspecialchars($_POST['beatmap_url']); // POST DATA
      preg_match('^https:\/\/osu\.ppy\.sh\/b\/(\d*)^', $beatmap_url, $matches); // REGEX
      $beatmap_url = $matches[0];
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
                  // FILE UPLOAD
                    $fileNameNew = "$player : $artist - $title [$version] ($uploader($ReplayCounter)).$fileActualExt";
                    $fileDestination = 'uploads/'.$fileNameNew;
                    move_uploaded_file($fileTmpName, $fileDestination);

                  // Insert DB
                    $newReplay = $db->prepare("INSERT INTO replays(visibility,replay_url,artist,title,version,creator,mode,difficultyrating,beatmap_id,beatmap_url,beatmapset_id,player,player_rank,country,player_id,player_url,uploader) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
                    $newReplay->execute(array($visibility,$fileNameNew,$artist,$title,$version,$creator,$mode,$difficultyrating,$beatmap_id,$beatmap_url,$beatmapset_id,$player,$player_rank,$country,$player_id,$player_url,$uploader));

                  // UPDATE COUNTER
                    $req = $db->prepare('SELECT replays FROM users WHERE id=?');
                    $req->execute(array($_SESSION['id']));
                    $results = $req->fetch();
                    $ReplayCounter = $results["replays"] + 1;
                    $updateReplayCounter = $db->prepare("UPDATE users SET replays=? WHERE id=?");
                    $updateReplayCounter->execute(array($ReplayCounter,$_SESSION['id']));

                    $page = htmlspecialchars($_GET['ref']);
                    header("Location: ../$page");
              }
              else
              {
                  die("Your file is too big.");
              }
          }
          else
          {
              die("There was an error uploading your file.");
          }
      }
      else
      {
          die("No .osr file found.");
      }

  }
  else
  {
    die("Input missing");
  }
}

?>
