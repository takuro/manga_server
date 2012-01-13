<?php

  /* 環境設定 */
  // [注意] ディレクトリを設定する箇所では、末尾に / （スラッシュ）は不要です。

  // ルートディレクトリ
  $path = pathinfo($_SERVER['SCRIPT_FILENAME']);
  define("APP_ROOT", $path["dirname"]);

  /*----------- 以下の設定を自分の環境に合わせる ----------*/

  // 漫画のファイルが格納されているディレクトリ
  // 公開されている必要はありません
  //define("COMIC_DIR", "../comics");
  define("COMIC_DIR", "../comics");

  // キャッシュディレクトリ
  // Web サーバを動かすユーザに書き込み権限を与えてください
  // 公開ディレクトリである必要があります。
  define("CACHE", "cache");

  // ディレクトリツリーを書きだすファイルパス
  // Web サーバを動かすユーザに書き込み権限を与えてください
  // 公開ディレクトリでないほうが望ましいです。
  define("DIRTREE", CACHE."/dir_tree");

  // ImageMagickを使ってサムネイルを生成する
  // 以下を true に変更してください。
  // ImageMagick がインストールされていない場合は、true にしないでください。
  // * shell_exec で convert コマンドを実行します。
  define("USEIMAGEMAGICK", false);

  // sqlite3 の実行ファイルまでのパス
  // sqlite3 実行ファイル自体を含みます
  define("SQLITE", APP_ROOT."/db/sqlite3");

  /*----------- 以下の設定はあまりいじらないで ----------*/

  // 対応する圧縮形式（この形式以外のファイルは読み込まない）
  // バージョン 0.1 では ZIP 形式のみ対応
  // （これ以上対応しないかも、あと小文字のみで）
  define("COMIC_EXT", "zip");

  // 対応する画像の形式
  // 大文字小文字は区別するので両方書く（検索が早いから）
  $image_ext = array("jpg", "jpeg", "png", "JPG", "JPEG", "PNG");

  // 一度にキャッシュにコピーする画像数
  // 大きくすると最初のページが表示されるのが遅くなるかも
  define("LOOKAHEAD", 300);

  // キャッシュディレクトリのサイズ制限
  // 設定したバイト数を超えると中身を空にする
  // デフォルト約 100 MB
  define("CACHELIMIT", 100000000);

  // 何番目の画像を表紙にするか
  // デフォルトは 1 枚目
  define("FORCOVER", 2);

  // サムネイルの最大幅
  define("MAXWIDTHTHUMB", 90);

  // サムネイルの最大高
  define("MAXHEIGHTTHUMB", 90);

  // サムネイルの品質
  // 70 〜 90 くらいで、最大 100
  define("THUMBQUALITY", 90);

  // サムネイルを保存するファイル
  // サムネイルは base64 で文字列になってる
  define("THUMBSFILE", CACHE."/thumbs.json");

  // データベースのファイルパス
  define("DB", APP_ROOT."/db/manga_server.db");

  // 先読みする画像数
  // default.js の preload_images も同じ数字にしてください。
  define("PRELOAD", 4);

  /*----------- 初期化 ----------*/
  require_once 'sqlite.php';
  if (!file_exists(DB)) {
    init_tables();
  }

?>
