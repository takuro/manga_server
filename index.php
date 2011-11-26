<?php
  require_once 'settings.php';
  require_once 'template.php';
  require_once 'functions.php';
  $dir = dir_tree();
  start_html();
?>
  <nav id="index">
    <?php
      foreach($dir as $id => $c) {
        echo '<a id="comic_'.$id.'" class="comic_title">'.basename($c, '.'.COMIC_EXT).'</a>';
      }
    ?>
  </nav>

  <article id="viewer">
    <div id="page_1" class="pages">
    </div>
    <div id="page_2" class="pages">
    </div>
    <div id="half_page">
      <a id="next">&lt;</a>
      <div id="half_page_image"></div>
      <a id="previous">&gt;</a>
    </div>
    <div class="clear"></div>
  </article>

  <nav id="menu">
    <a id="next_half_page">1 ページ進む</a>
    <a id="switch_half_page">単ページ切替</a>
    <a id="paint_index">蔵書一覧</a>
    <a id="previous_half_page">1 ページ戻る</a>
  </nav>

  <input type="hidden" id="current_title" name="current_title" value="" />
  <input type="hidden" id="current_page" name="current_page" value="" />

<?php
  end_html();
?>
