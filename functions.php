<?php

function is_image($filename, $image_ext) {
  $ext = substr(strrchr($filename, '.'), 1);

  return in_array($ext, $image_ext);

}

function cache_clean() {
  if (dir_size(CACHE) > CACHELIMIT) {
    exec('rm -f '.CACHE.'/*');
    return true;
  } else {
    return false;
  }
}

function dir_size($dir) {
  $handle = opendir($dir);

  $mas = 0;
  while ($file = readdir($handle)) {
    if ($file != '..' && $file != '.' && !is_dir($dir.'/'.$file)) {
      $mas += filesize($dir.'/'.$file);
    } else if (is_dir($dir.'/'.$file) && $file != '..' && $file != '.') {
      $mas += dir_size($dir.'/'.$file);
    }
  }
  closedir($handle);

  return $mas;
}

function dir_tree() {
  return glob(COMIC_DIR.'/*.'.COMIC_EXT, GLOB_ERR);
}

// テスト用
function d($value) {
  echo '<h3>var_dump</h3>';
  var_dump($value);

  //echo '<h3>print_r</h3>';
  //print_r($value);
}
?>
