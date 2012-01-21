<?php
  header("Content-Type: application/json; charset=utf-8");

  require_once 'settings.php';
  require_once 'functions.php';
  require_once 'sqlite.php';

  $data = select("SELECT id, cover FROM comics;");
  $covers = Array();
  foreach($data as $d) {
    $covers[] = '{"id":"'.$d["id"].'", "data":"'.$d["cover"].'"}';
  }

  echo '['.implode(',', $covers).']';
?>
