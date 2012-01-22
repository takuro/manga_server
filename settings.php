<?php

  /* 環境設定 */
  // [注意] ディレクトリを設定する箇所では、末尾に / （スラッシュ）は不要です。

  // ルートディレクトリ
  $path = pathinfo($_SERVER['SCRIPT_FILENAME']);
  define("APP_ROOT", $path["dirname"]);

  /*----------- 以下の設定を自分の環境に合わせる ----------*/

  // 漫画のファイルが格納されているディレクトリ
  // 公開されている必要はありません
  define("COMIC_DIR", "../comics");

  // キャッシュディレクトリ
  // Web サーバを動かすユーザに書き込み権限を与えてください
  // 公開ディレクトリである必要があります。
  define("CACHE", "cache");

  // データベースファイルが格納されるディレクトリ
  define("DB_DIR", APP_ROOT."/db");

  // データベースのファイルパス
  define("DB", DB_DIR."/manga_server.db");

  // 何番目の画像を表紙にするか
  // デフォルトは 1 枚目
  define("FORCOVER", 1);

  // 先読みする画像数
  // default.js の preload_images も同じ数字にしてください。
  define("PRELOAD", 4);

  // キャッシュディレクトリのサイズ制限
  // 設定したバイト数を超えると中身を空にする
  // デフォルト約 100 MB
  define("CACHELIMIT", 100000000);

  // ImageMagickを使ってサムネイルを生成する
  // 以下を true に変更してください。
  // ImageMagick がインストールされていない場合は、true にしないでください。
  // * shell_exec で convert コマンドを実行します。
  define("USEIMAGEMAGICK", false);

  // サムネイルの作成を非同期で実施する
  // サーバ上で PHP コマンドが使える場合のみ true にしてください。
  // [注意] Windows での動作未確認。
  define("ASYNC_MAKE_THUMBNAILS", false);

  /*----------- 以下の設定はあまりいじらないで ----------*/

  // 対応する圧縮形式（この形式以外のファイルは読み込まない）
  // バージョン 0.1 では ZIP 形式のみ対応
  // （これ以上対応しないかも、あと小文字のみで）
  define("COMIC_EXT", "zip");

  // 対応する画像の形式
  // 大文字小文字は区別するので両方書く（検索が早いから）
  $image_ext = array("jpg", "jpeg", "png", "JPG", "JPEG", "PNG");

  // サムネイルの最大幅
  define("MAXWIDTHTHUMB", 90);

  // サムネイルの最大高
  define("MAXHEIGHTTHUMB", 90);

  // サムネイルの品質
  // 10 から 100 で指定、大きいほど高画質で重い
  define("THUMBQUALITY", 90);

  // サイト公開用
  define("DONT_MAKE_THUMBNAIL", false);

  /*----------- 初期化 ----------*/
  require_once 'sqlite.php';
  if (!file_exists(DB_DIR)) {
    die("Fatal error : ".DB_DIR." is not exist.");
  }

  if (!is_writable(DB_DIR)) {
    die("Fatal error : Permission denied. ( ".DB_DIR." )");
  }

  if (!file_exists(CACHE)) {
    die("Fatal error : ".CACHE." is not exist.");
  }

  if (!is_writable(CACHE)) {
    die("Fatal error : Permission denied. ( ".CACHE." )");
  }

  if (!file_exists(DB)) {
    init_tables();
  }

?>
