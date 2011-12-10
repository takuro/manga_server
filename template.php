<?php

include_once "functions.php";

function start_html() {
  echo '
    <!DOCTYPE HTML>
      <html lang="ja">
        <head>
          <meta charset="utf-8" />';

  if (is_ipad() || is_iphone()) {
    echo '<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=3.0; user-scalable=yes" />';
    echo '<meta name="apple-mobile-web-app-capable" content="yes" />';
    echo '<meta name="apple-mobile-web-app-status-bar-style" content="black" />';
  }

  echo '
          <title>Manga Server</title>
          <link rel="stylesheet" href="style/reset.css" />
          <link rel="stylesheet" href="style/default.css" />
        </head>
      <body>';
}

function end_html() {
  echo '
      <script src="http://code.jquery.com/jquery.min.js"></script>
      <script src="js/jquery.cookie.js"></script>
      <script src="js/default.js"></script>';

  if (is_ipad()) {
    echo '<script src="js/ios.js"></script>';
  }

  echo '
    </body>
    </html>';
}
?>
