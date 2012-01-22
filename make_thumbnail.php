<?php
  require_once 'settings.php';
  require_once 'functions.php';
  require_once 'template.php';
  require_once 'sqlite.php';

  if (DONT_MAKE_THUMBNAIL) {
    die("Fatal error : Can not make thumbnail.");
    return;
  }

  $shell_exec = true;
  if (!is_null($argv) && !empty($argv[1])) {
    $shell_exec = false;
  }
  
  if (ASYNC_MAKE_THUMBNAILS && $shell_exec) {
    exec("php ".APP_ROOT."/make_thumbnail.php true > /dev/null &");
    return;
  }

  // データベース再構築
  cache_clean();
  $dir = get_dir_tree();
  $files = count($dir);

  for ($i = 0; $i < $files; $i++) {
    $count = 1;
    $zip_file = COMIC_DIR."/".$dir[$i];
    $comic = zip_open($zip_file);
    if (is_resource($comic)) { 
      $file_name = "";
      $count = 1;
      while (($entry = zip_read($comic)) !== false) { 
        $file_name = zip_entry_name($entry);
        $file_name = mb_convert_encoding($file_name, "UTF-8", $enc);
        // もう走査しなくていい
        if ($count > FORCOVER) {
          break;
        }

        // 画像か否か
        if (!is_image($file_name, $image_ext)) {
          continue;
        }

        // サムネイルを作るべき画像か
        if ($count == FORCOVER) {
          $data = zip_entry_read($entry, zip_entry_filesize($entry));
          $ext = get_ext($file_name);
          $thumb = array(
            "id" => $i+1,
            "zip" => $zip_file,
            "filepath" => CACHE."/thumb.".$ext,
            "ext" => $ext
          );
          file_put_contents($thumb["filepath"], $data);

          $r = make_thumbnail($thumb);
          if ($r) {
            save_thumbnail($thumb);
          }
        }
        $count++;
      }
    } else { 
      //die("[ERR]ZIP_OPEN : ".$zip_file); 
      // ここに代替画像
    }
    zip_close($comic);
  }

  echo 'ok';
  //header('Location: index.php');

?>
