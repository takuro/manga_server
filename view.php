<?php

  header("Content-Type: application/json; charset=utf-8");

  // [ToDo]
  // * バリデート（個人利用想定なので優先度低）

  require_once 'settings.php';
  require_once 'functions.php';

  $dir = get_dir_tree();
  cache_clean();
  
  // 漫画 ID
  $id = $_GET["id"];
  $id = intval(str_replace('comic_', '', $id));

  // 現在のページ
  $page = intval($_GET["page"]);

  // ZIP ファイル読み込み
  $count = 0;
  $read_count = 0;
  $zip_path = COMIC_DIR."/".$dir[$id];
  $manga_title = get_filename_without_ext($zip_path);

  $comic = zip_open($zip_path);
  if (is_resource($comic)) { 
    $inzip_path = "";
    while (($entry = zip_read($comic)) !== false) { 
      $inzip_path = zip_entry_name($entry);
#     $inzip_path = mb_convert_encoding($inzip_path, "UTF-8", $enc);
      $cache_name = md5($zip_path."/".$inzip_path).'.'.get_ext($inzip_path);

      // もう走査しなくていい
      if ($count > $page + LOOKAHEAD) {
        break;
      }

      // 画像か否か
      if (!is_image($inzip_path, $image_ext)) {
        continue;
      }

      // 画像読み込むべきか
      if ($count == $page || $count == $page + 1) {
        $pages[$read_count++] = $cache_name;
      }

      // 画像をキャッシュに格納すべきか
      if ($count >= $page && $count < $page + LOOKAHEAD) {
        $data = zip_entry_read($entry, zip_entry_filesize($entry));
        file_put_contents(CACHE.'/'.$cache_name, $data);
      }

      $count++;
    } 
  } else { 
    die("[ERR]ZIP_OPEN : ".$zip_path); 
  }

  zip_close($comic);

  // 表示するページのパスを返却
  if ($read_count < 1) {
    echo '{"msg": "ERROR"}';
  } else {
    $response = '{"title":"'.$manga_title.'", "files":[';


    $send_imgs = 2;
    for ($i = 0; $i < $send_imgs; $i++) {
      $path = CACHE.'/'.$pages[$i];
      $response .= '"'.$path.'"';
      if ($i + 1 < $send_imgs) {
        $response .= ',';
      } else {
        $response .= ']}';
      }
    }

    echo $response;
  }
?>

