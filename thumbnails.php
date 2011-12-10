<?php
  header("Content-Type: application/json; charset=utf-8");

  require_once 'settings.php';
  require_once 'functions.php';

  if (!$handle = fopen(THUMBSFILE, "rb")) {
    die("[ERR]FILE OPEN : THUMBSFILE");
  }

  $data = array();
  while (($buffer = fgets($handle, 512000)) !== false) {
    $data[] = $buffer;
  }
  if (!feof($handle)) {
    echo "Error: unexpected fgets() fail\n";
  }
  fclose($handle);

  echo '['.implode(',', $data).']';
?>
