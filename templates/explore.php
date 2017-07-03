<?php
session_start();
require '../libs/db.php';

$replays = $db->prepare('SELECT * FROM replays WHERE visibility = ? ORDER BY id DESC');
$replays->execute(array("public"));
?>

<?php while($replay = $replays->fetch()): ?>
    <div class="col s12 m6 l4">
      <div class="card grey lighten-3">
          <div class="card-image">
            <div class="chip">
              <?=$replay['dl_count']?>
            </div>
            <img src="https://assets.ppy.sh//beatmaps/<?=$replay["beatmapset_id"]?>/covers/card.jpg">
            <span class="card-title truncate"><?=$replay["title"]?><br/><?=$replay["artist"]?> by <?=$replay["creator"]?></span>
            <?php if (strlen($replay['youtube_url']) != 0): ?>
              <div class="btn-floating halfway-fab waves-effect waves-light deep-purple accent-2 list-fab"><i class="material-icons">more_vert</i></div>
                <div class="list-menu">
                  <ul>
                    <a href="libs/download.php?id=<?=$replay['id']?>">
                      <li>Download</li>
                    </a>
                    <a href="<?=$replay['youtube_url']?>">
                      <li>View</li>
                    </a>
                  </ul>
                </div>
            <?php else: ?>
              <a href="libs/download.php?id=<?=$replay['id']?>" class="btn-floating halfway-fab waves-effect waves-light deep-purple accent-2"><i class="material-icons">play_for_work</i></a>
            <?php endif; ?>
          </div>
          <div class="card-content center-align">
            <p>
              Played by <?=$replay["player"]?> (#<?=$replay['player_rank']?>)
              <br/>
              On [<?=$replay["version"]?>] (<?=$replay["difficultyrating"]?>*)
              <br/>
            </p>
          </div>
        </div>
    </div>
<?php endwhile; ?>
