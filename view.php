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
  $zip_file = trim(COMIC_DIR."/".$dir[$id]);
  $comic = zip_open($zip_file);
  if (is_resource($comic)) { 
    $file_name = "";
    while (($entry = zip_read($comic)) !== false) { 
      $file_name = zip_entry_name($entry);
      $file_name = mb_convert_encoding($file_name, "UTF-8", $enc);

      // もう走査しなくていい
      if ($count > $page + LOOKAHEAD) {
        break;
      }

      // 画像か否か
      if (!is_image($file_name, $image_ext)) {
        continue;
      }

      // 画像読み込むべきか
      if ($count == $page || $count == $page + 1) {
        $pages[$read_count++] = $file_name;
      }

      // 画像をキャッシュに格納すべきか
      if ($count >= $page && $count < $page + LOOKAHEAD) {
        $dirname = dirname($file_name);

        if ($dirname != '.' && !file_exists(CACHE.'/'.$dirname)) {
          mkdir(CACHE.'/'.$dirname, 0777, true);
        }

        $data = zip_entry_read($entry, zip_entry_filesize($entry));
        file_put_contents(CACHE.'/'.$file_name, $data);

        /*
        大きい画像にはあんまり有効じゃないのね、base64
        $images[$read_count]["ext"] = get_ext($file_name);
        $images[$read_count++]["data"] = base64_encode($data);
        */
      }

      $count++;
    } 
  } else { 
    die("[ERR]ZIP_OPEN : ".$zip_file); 
  }

  zip_close($comic);

  // 表示するページのパスを返却
  if ($read_count < 1) {
    echo '{"msg": "ERROR"}';
  } else {
    $response = '{"title":"'.trim(basename($dir[$id])).'", "files":[';
    $response .= '"'.CACHE.'/'.$pages[0].'", "'.CACHE.'/'.$pages[1].'"]}';
    /*
    $response .= '{"ext": "'.$images[0]["ext"].'", "data": "'.$images[0]["data"].'"}, ';
    $response .= '{"ext": "'.$images[1]["ext"].'", "data": "'.$images[1]["data"].'"}]}';
    */
    echo $response;
  }
?>

