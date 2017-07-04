<?php
session_start();
require '../libs/db.php';

$replays = $db->prepare('SELECT * FROM replays WHERE visibility = ? ORDER BY dl_count DESC LIMIT 5');
$replays->execute(array("public"));
?>

<?php while($replay = $replays->fetch()): ?>
  <?php
    $isfav = $db->prepare('SELECT * FROM favorites WHERE user = ? AND replay = ?');
    $isfav->execute(array($_SESSION['id'],$replay['id']));
  ?>
    <div class="col s12 m6 offset-m6 l4 offset-l4">
      <div class="card grey lighten-3">
          <div class="card-image">
            <div class="chip">
              <?=$replay['dl_count']?>
            </div>
            <img src="https://assets.ppy.sh//beatmaps/<?=$replay["beatmapset_id"]?>/covers/card.jpg">
            <span class="card-title truncate"><?=$replay["title"]?><br/><?=$replay["artist"]?> by <?=$replay["creator"]?></span>
              <div class="btn-floating halfway-fab waves-effect waves-light deep-purple accent-2 list-fab"><i class="material-icons">more_vert</i></div>
              <div class="list-menu">
                <ul>
                  <a href="libs/download.php?id=<?=$replay['id']?>">
                    <li>Download</li>
                  </a>
                  <?php if (strlen($replay['youtube_url']) != 0): ?>
                    <a href="<?=$replay['youtube_url']?>">
                      <li>View</li>
                    </a>
                  <?php endif; ?>
                  <a href="libs/favorite.php?id=<?=$replay['id']?>&token=<?=$_SESSION['token']?>&ref=<?=$page?>">
                    <?php if (isset($_SESSION['auth'])): ?>
                      <?php if ($isfav->rowCount() == 0): ?>
                        <li>Add to favorite</li>
                      <?php else: ?>
                        <li>Remove from favorites</li>
                      <?php endif; ?>
                    <?php endif; ?>
                  </a>
                </ul>
              </div>
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
