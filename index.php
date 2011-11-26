<?php
  require_once 'settings.php';
  require_once 'template.php';
  require_once 'functions.php';

  dir_tree();
  $tree = get_dir_tree();
  start_html();
?>

  <article id="viewer">
    <section id="full_page">
      <div id="page_1"></div>
      <div id="page_2"></div>
      <div class="clear"></div>
    </section>

    <section id="half_page">
      <div class="next clickable"></div>
      <div id="half_page_image"></div>
      <div class="previous clickable"></div>
      <div class="clear"></div>
    </section>
  </article>

  <nav id="index">
    <?php
      $count = count($tree);
      $previous_root = array();
      for ($i = 0; $i < $count; $i++) {
        $path = explode("/", $tree[$i]);

        if (count($previous_root) > 1 && $path[0] !== $previous_root[0]) {
          echo "<br />";
        }
        echo '<a id="comic_'.$i.'" class="comic_title">'.$tree[$i].'</a>';
        $previous_root = $path;
      }
    ?>
  </nav>

  <nav id="menu">
    <a class="next controller">1 ページ進む</a>
    <a class="next_file controller">次のファイル</a>
    <a id="switch_half_page" class="controller">単ページ切替</a>
    <a id="paint_index">蔵書一覧</a>
    <a class="previous controller">1 ページ戻る</a>
    <a class="previous_file controller">前のファイル</a>
    <div class="clear"></div>
  </nav>

  <input type="hidden" id="current_title" name="current_title" value="" />
  <input type="hidden" id="current_page" name="current_page" value="" />

<?php
  
  end_html();
?>
