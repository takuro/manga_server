<?php
function start_html() {
  echo '
    <!DOCTYPE HTML>
      <html lang="ja">
        <head>
          <meta charset="utf-8" />
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
      <script src="js/default.js"></script>
    </body>
    </html>';
}
?>
