<?php
  header("Content-Type: application/json; charset=utf-8");

  require_once 'settings.php';
  require_once 'functions.php';
  require_once 'sqlite.php';

  $r = select("SELECT id, cover FROM comics;");
  $data = explode("\n", $r);

  $covers = Array();
  foreach($data as $d) {
    $cover = explode(":/:", $d);
    $covers[] = '{"id":"'.trim($cover[0]).'", "data":"'.trim($cover[1]).'"}';
  }

  echo '['.implode(',', $covers).']';
?>
