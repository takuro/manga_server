$(function(){
  // 蔵書一覧 ON / OFF
  $("#paint_index").click(function(){
    var index = $("#index");
    if (index.css('display') == 'none') {
      index.show();
    } else {
      index.hide();
    }
  });

  // 蔵書一覧からクリック
  $(".comic_title").click(function(){
    var _this = $(this);
    var id = _this.attr("id");

    // 描画
    get_page(id, 0);
    $("#index").hide();
  });

  // 次のページ表示
  $("#page_2").click(function(){
    var id = get_current_title();
    var page = get_current_page() + 2;
    get_page(id, page);
  });

  // 1 ページだけ進む
  $("#next_half_page").click(function(){
    var id = get_current_title();
    var page = get_current_page() + 1;
    get_page(id, page);
  });

  // 前のページ表示
  $("#page_1").click(function(){
    var id = get_current_title();
    var page = get_current_page() - 2;
    if (page < 0) {
      page = 0;
    }
    get_page(id, page);
  });

  // 1 ページ戻る
  $("#previous_half_page").click(function(){
    var id = get_current_title();
    var page = get_current_page() - 1;
    if (page < 0) {
      page = 0;
    }
    get_page(id, page);
  });

  // ページの取得
  function get_page(id, page) {
    $.getJSON( "view.php?id=" + id + "&page=" + page, function(result) {
      set_current_title(id);
      set_current_page(page);

      paint("#page_1", result[0]);
      paint("#page_2", result[1]);
    });
  }

  // 現在表示している漫画の ID を保存
  function set_current_title(title) {
    $("#current_title").attr("value", title);
  }

  // 現在表示している漫画の ID を取得
  function get_current_title() {
    return $("#current_title").attr("value");
  }

  // 現在表示している漫画のページを保存
  function set_current_page(page) {
    $("#current_page").attr("value", page);
  }

  // 現在表示している漫画のページを取得
  function get_current_page() {
    return parseInt($("#current_page").attr("value"));
  }

  // 画像描画
  function paint(id, path) {
    $(id).html('<img src="' + path + '" alt="" />');
    image_size_reduction(id);
  }

  // 画像縮小
  function image_size_reduction(id) {
    var window_width = $(window).width();
    var image_width = window_width / 2;

    var window_height = $(window).height();
    var image_height = window_height - 28;

    $(id + " img").css({
      "max-width": image_width + "px",
      "height": image_height + "px",
    });
  }

});

