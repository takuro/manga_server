var cookie_key = 'is_half_page_mode';

$(function(){

  change_half_mode();

  // 単ページ切り替え
  $("#switch_half_page").click(function(){
    var value = is_half();

    if (value) {
      var new_value = 'false';
    } else {
      var new_value = 'true';
    }
    $.cookie(cookie_key, new_value);
    change_half_mode();

    image_size_reduction();
  });

  function is_half() {
    var mode = $.cookie(cookie_key);

    if (mode === null) {
      return false;
    } else {
      if (mode == 'true') {
        return true;
      } else {
        return false;
      }
    }
  }

  function change_half_mode() {
    if (is_half()) {
      // 単ページに切り替え
      $(".pages").hide();
      $("#half_page").show();
    } else {
      // 複数ページに切り替え
      $(".pages").show();
      $("#half_page").hide();
    }
  }

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
  $("#next_half_page, #next").click(function(){
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
  $("#previous_half_page, #previous").click(function(){
    var id = get_current_title();
    var page = get_current_page() - 1;
    if (page < 0) {
      page = 0;
    }
    get_page(id, page);
  });

  // ページの取得
  function get_page(id, page) {
    var query = "view.php?id=" + id + "&page=" + page;

    $.getJSON(query, function(result) {
      set_current_title(id);
      set_current_page(page);

      paint("#half_page_image", result[0]);
      paint("#page_1", result[0]);
      paint("#page_2", result[1]);
      image_size_reduction();
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
  }

  // 画像縮小
  function image_size_reduction(id) {
    var window_width = $(window).width();
    var image_width = window_width / 2;

    var window_height = $(window).height();
    var image_height = window_height - 28;

    $("#half_page img").css({
      "height": image_height + "px",
    });
    $("#half_page a").css({
      "height": image_height + "px",
    });

    $(".pages img").css({
      "max-width": image_width + "px",
      "height": image_height + "px",
    });
  }

});

