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
      <div id="page_1" class="right_page"></div>
      <div id="page_2" class="left_page"></div>
      <div class="clear"></div>
    </section>

    <section id="half_page">
      <div class="controllers">
        <div class="next next_control left_control"></div>
        <div class="previous previous_control right_control"></div>
      </div>
      <div id="half_page_image"></div>
      <div class="clear"></div>
    </section>
  </article>

  <nav id="menu">
<?php
  if (is_iphone()) {
?>
    <a class="controller next next_control left_control">1 ページ進む</a>
    <a class="controller previous previous_control right_control">1 ページ戻る</a>
    <div class="clear"></div>
    <a class="controller next_file next_control left_control">次のファイル</a>
    <a class="controller previous_file previous_control right_control">前のファイル</a>
    <div class="clear"></div>
    <a id="paint_index" class="selected">蔵書一覧</a>
    <a id="paint_settings">設定</a>
<?php
  } else {
?>
    <a class="controller next next_control left_control">1 ページ進む</a>
    <a class="controller next_file next_control left_control">次のファイル</a>
    <a id="switch_half_page" class="controller ">単ページ切替</a>
    <a id="paint_index" class="selected">蔵書一覧</a>
    <a id="paint_settings">設定</a>
    <a class="controller previous previous_control right_control">1 ページ戻る</a>
    <a class="controller previous_file previous_control right_control">前のファイル</a>
<?php
  }
?>
  </nav>

  <nav id="index">
    <?php
      $count = count($tree);
      for ($i = 0; $i < $count; $i++) {
        echo '<a id="comic_'.$i.'" class="comic_title" tabindex="'.($i+1).'" title="'.$tree[$i].'">'.$tree[$i].'</a>';
      }
    ?>
  </nav>

  <section id="settings">
    <ul>
      <li id="right_click_to_next_wrapper">
        <?php
          if (empty($_COOKIE["right_click_to_next"])) {
            $checked = "false";
          } else {
            $checked = $_COOKIE["right_click_to_next"];
          }

          if ($checked == "true") { ?>
            <input type="checkbox" name="right_click_to_next" id="right_click_to_next" value="1" checked="checked" />
        <?php
          } else { ?>
            <input type="checkbox" name="right_click_to_next" id="right_click_to_next" value="1" />
        <?php
          } ?>
        <label for="right_click_to_next">
          画面の右側をクリックして次のページに移動する
        </label><br />
        <span class="notice">
          標準設定：画面の左側をクリックして次のページに移動
        </span>
      </li>
      <li id="right_paginate_wrapper">
        <?php
          if (empty($_COOKIE["right_paginate"])) {
            $checked = "false";
          } else {
            $checked = $_COOKIE["right_paginate"];
          }

          if ($checked == "true") { ?>
            <input type="checkbox" name="right_paginate" id="right_paginate" value="1" checked="checked" />
        <?php
          } else { ?>
            <input type="checkbox" name="right_paginate" id="right_paginate" value="1" />
        <?php
          } ?>
        <label for="right_paginate">
          左のページから読む
        </label><br />
        <span class="notice">
          標準設定：右のページから読む
        </span>
      </li>

      <li id="background_color">
        背景：
        <?php
          if (empty($_COOKIE["background_color"])) {
            $bg_color = "false";
          } else {
            $bg_color = $_COOKIE["background_color"];
          }

          if ($bg_color == "false") {
        ?>
          <input type="radio" name="background_color" id="background_white" value="1" checked="checked" />
          <label for="background_white"> 明るい </label>
          <input type="radio" name="background_color" id="background_black" value="1" />
          <label for="background_black"> 暗い </label>
        <?php
          } else {
        ?>
          <input type="radio" name="background_color" id="background_white" value="1" />
          <label for="background_white"> 明るい </label>
          <input type="radio" name="background_color" id="background_black" value="1" checked="checked" />
          <label for="background_black"> 暗い </label>
        <?php
          }
        ?>
      </li>

      <li>
        <a href="make_thumbnail.php">漫画の表紙を生成する</a>
        <br />
        <span class="notice">
          まだきちんと動かないかもしれません。漫画ファイルが多いと時間がかかります。
        </span>
      </li>
    </ul>
  </section>

  <input type="hidden" id="current_title" name="current_title" value="" />
  <input type="hidden" id="current_page" name="current_page" value="" />

<?php
  end_html();
?>
