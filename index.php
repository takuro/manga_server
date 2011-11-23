<?php
  require_once 'settings.php';
  require_once 'template.php';
  $dir = glob(COMIC_DIR.'/*.'.COMIC_EXT, GLOB_ERR);

  start_html();
?>

  <nav id="menu">
    <a id="next_half_page">1 ページ進む</a>
    <a id="paint_index">蔵書一覧</a>
    <a id="previous_half_page">1 ページ戻る</a>
  </nav>

  <nav id="index">
    <?php
      foreach($dir as $id => $c) {
        echo '<a id="comic_'.$id.'" class="comic_title">'.basename($c, '.'.COMIC_EXT).'</a>';
      }
    ?>
  </nav>

  <article id="viewer">
    <div id="page_1">
    </div>
    <div id="page_2">
    </div>
    <div class="clear"></div>
  </article>

  <input type="hidden" id="current_title" name="current_title" value="" />
  <input type="hidden" id="current_page" name="current_page" value="" />

<?php
  end_html();
?>
