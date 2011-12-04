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
    <span class="controller">
      <a class="next next_control left_control">1 ページ進む</a>
    </span>

    <span class="controller">
      <a class="next_file next_control left_control">次のファイル</a>
    </span>

    <span class="controller">
      <a id="switch_half_page">単ページ切替</a>
    </span>
    <a id="paint_index">蔵書一覧</a>
    <a id="paint_settings">設定</a>

    <span class="controller">
      <a class="previous previous_control right_control">1 ページ戻る</a>
    </span>

    <span class="controller">
      <a class="previous_file previous_control right_control">前のファイル</a>
    </span>

    <div class="clear"></div>
  </nav>

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

  <section id="settings">
    <ul>
      <li id="right_click_to_next_wrapper">
        <?php
          $checked = $_COOKIE["right_click_to_next"];
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
          $checked = $_COOKIE["right_paginate"];
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
    </ul>
  </section>

  <input type="hidden" id="current_title" name="current_title" value="" />
  <input type="hidden" id="current_page" name="current_page" value="" />

<?php
  
  end_html();
?>
