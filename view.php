<?php

  header("Content-Type: application/json; charset=utf-8");

  // [ToDo]
  // * バリデート（個人利用想定なので優先度低）

  require_once 'settings.php';
  require_once 'functions.php';
  require_once 'sqlite.php';

  $dir = get_dir_tree();
  cache_clean();
  
  // 漫画 ID
  $id = $_GET["id"];
  $id = intval(str_replace('comic_', '', $id));
  if ($id < 1) {
    $id = 1;
  }

  // 現在のページ
  $page = intval($_GET["page"]);
  if ($page < 1) {
    $page = 1;
  }

  $zip_path = COMIC_DIR."/".$dir[$id-1];
  $manga_title = get_filename_without_ext($zip_path);

  // cache ディレクトリに ZIP 内のファイルを展開
  if (!cached($id)) {
    caching($id, $zip_path, $image_ext);
  }

  // 表示するページのパスを返却
  $response  = '{"title":"'.get_title($id).'", "pages":"'.get_pages($id).'", "files":[';
  $load_images = $page + PRELOAD + 2;
  for ($i = $page; $i <= $load_images; $i++) {
    $response .= '"'.get_filepath($id, $i).'"';
    if ($i == $load_images) {
      $response .= ']}';
    } else {
      $response .= ',';
    }
  }
  echo $response;

  function cached($comics_id) {
    $r = select("SELECT id from images where comics_id = ".$comics_id." LIMIT 1");
    if (empty($r)) {
      return false;
    } else {
      return true;
    }
  }

  // ZIP ファイル読み込み
  function caching($comics_id, $zip_path, $image_ext) {
    $comic = zip_open($zip_path);
    if (!is_resource($comic)) { 
      die("[ERR]ZIP_OPEN : ".$zip_path); 
    }

    $inzip_path = "";
    $count = 0;
    $files = null;

    $db = new SQLite3(DB);
    $db->exec("BEGIN DEFERRED;");
    while (($entry = zip_read($comic)) !== false) { 
      $inzip_path = zip_entry_name($entry);
      $cache_name = md5($zip_path."/".$inzip_path).'.'.get_ext($inzip_path);

      // 画像か否か
      if (!is_image($inzip_path, $image_ext)) {
        continue;
      }

      $data = zip_entry_read($entry, zip_entry_filesize($entry));
      $filepath = CACHE.'/'.$cache_name;
      file_put_contents($filepath, $data);

      $count++;
      query("INSERT INTO images (comics_id, page, filepath) VALUES (".$comics_id.", ".$count.", '".$filepath."')", $db);
    } 
    zip_close($comic);

    query("UPDATE comics SET pages = ".$count." WHERE id = ".$comics_id, $db);
    $db->exec("COMMIT;");
  }
?>

