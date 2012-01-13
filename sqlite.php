<?php
  require_once 'settings.php';
  require_once 'functions.php';

  function init_tables() {
    create_tables();
    dir_tree();
  }

  function create_tables() {
    if (file_exists(DB)) {
      $cmd = "rm -f ".DB;
      shell($cmd);
    }

    query("BEGIN");
    query("CREATE TABLE comics (id INTEGER PRIMARY KEY AUTOINCREMENT, title TEXT, pages INTEGER, zip_path TEXT, cover TEXT);");
    query("CREATE TABLE images (id INTEGER PRIMARY KEY AUTOINCREMENT, comics_id INTEGER, page INTEGER, filepath TEXT);");
    query("COMMIT");

    return true;
  }

  function query($query) {
    $cmd = SQLITE.' '.DB.' "'.$query.'"';
    return shell($cmd);
  }

  function select($query) {
    $cmd = SQLITE.' -separator ":/:" '.DB.' "'.$query.'"';
    $r = shell_exec($cmd);
    if (is_null($r)) {
      return false;
    } else {
      return trim($r);
    }
  }

  function shell($cmd) {
    $r = shell_exec($cmd);
    if (is_null($r)) {
      return false;
    } else {
      return $r;
    }
  }

  // ディレクトリツリーを取得
  function get_dir_tree() {
    $tree = select("select zip_path from comics");
    return explode("\n", $tree);
  }

  // 漫画のタイトルを取得
  function get_title($comics_id) {
    $r = select("SELECT title FROM comics WHERE id = ".$comics_id);
    return trim($r);
  }

  // 漫画のページ数を取得
  function get_pages($comics_id) {
    $r = select("SELECT pages FROM comics WHERE id = ".$comics_id);
    return intval($r);
  }

  // 漫画のページを取得
  function get_filepath($comics_id, $page) {
    $r = select("SELECT filepath FROM images WHERE comics_id = ".$comics_id." AND page = ".$page);
    return trim($r);
  }
?>
