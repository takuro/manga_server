<?php
  header("Content-Type: application/json; charset=utf-8");

  // [ToDo]
  // * バリデート（個人利用想定なので優先度低）

  require_once 'settings.php';
  require_once 'functions.php';

  $dir = dir_tree();
  cache_clean();
  
  // 漫画 ID
  $id = $_GET["id"];
  $id = intval(str_replace('comic_', '', $id));

  // 現在のページ
  $page = intval($_GET["page"]);

  // ZIP ファイル読み込み
  $count = 0;
  $read_count = 0;
  $comic = zip_open($dir[$id]);
  if (is_resource($comic)) { 
    $file_name = "";
    while (($entry = zip_read($comic)) !== false) { 
      $file_name = zip_entry_name($entry);

      // 先読みすべきページ数を超えてる
      if ($count >= $page + LOOKAHEAD) {
        break;
      }

      // 画像か否か
      if (!is_image($file_name, $image_ext)) {
        continue;
      }

      // ファイル名を格納
      if ($count == $page || $count == $page + 1) {
        $pages[$read_count++] = $file_name;
      }

      // 画像をキャッシュに格納すべきか
      if ($count >= $page && $count < $page + LOOKAHEAD) {
        file_put_contents(
          CACHE.'/'.$file_name,
          zip_entry_read($entry, zip_entry_filesize($entry))
        );
      }

      $count++;
    } 
  } else { 
    die("[ERR]ZIP_OPEN"); 
  }

  zip_close($comic);

  // 表示するページのパスを返却
  if ($read_count < 1) {
    echo "NOPAGE";
  } else {
    echo '["'.CACHE.'/'.$pages[0].'", "'.CACHE.'/'.$pages[1].'"]';
  }
?>

