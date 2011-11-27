var cookie_key = 'is_half_page_mode';

$(function(){

  var keyboard_shortcuts = {
    "37" : "move_page('next', ",
    "65" : "move_page('next', ",
    "39" : "move_page('previous', ",
    "68" : "move_page('previous', ",
  }

  window_resize();
  change_half_mode();

  // 単ページ切り替え
  $("#switch_half_page").click(function(){
    var value = is_half();

    if (value) {
      set_half_page_mode(false);
    } else {
      set_half_page_mode(true);
    }
    change_half_mode();
    image_size_reduction();
  });

  // ウィンドウのリサイズを取得
  $(window).resize(function() {
    var is_half_mode = is_half();
    window_resize();
    change_half_mode();

    if (is_half() != is_half_mode) {
      image_size_reduction();
    }
  });

  // Keyboard shortcut.
  $(window).keydown(function(e){
    var _event = keyboard_shortcuts[e.keyCode];
    var page = 2;
    if (is_half()) {
      page = 1;
    }
    if (_event != null) { eval(_event + page + ')'); }
  });

  function window_resize() {
    var window_width = $(window).width();
    var window_height = $(window).height();

    if (window_width < window_height) {
      set_half_page_mode(true);
    } else {
      set_half_page_mode(false);
    }
  }

  function set_half_page_mode(to_half) {
    if (to_half === true) {
      var new_value = 'true';
    } else {
      var new_value = 'false';
    }
    $.cookie(cookie_key, new_value);
  }

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
      $("#full_page").hide();
      $("#half_page").show();
    } else {
      // 複数ページに切り替え
      $("#full_page").show();
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
    $("#viewer").animate({ height: "show" }, "slow");
  });

  // 次のページ表示
  $("#page_2").click(function(){ move_page("next", 2); });
  $(".next").click(function(){ move_page("next", 1); });

  // 前のページ表示
  $("#page_1").click(function(){ move_page("previous", 2); });
  $(".previous").click(function(){ move_page("previous", 1); });

  // 次のファイルへ
  $(".next_file").click(function(){ move_file("next", 1); });

  // 前のファイルへ
  $(".previous_file").click(function(){ move_file("previous", 1); });

  // ページ移動
  function move_page(toward, move) {
    var id = get_current_title();
    if (toward === "next") {
      var page = get_current_page() + move;
    } else if(toward === "previous") {
      var page = get_current_page() - move;
    }
    if (page < 0) {
      page = 0;
      move_file("previous", 1);
    } else {
      get_page(id, page);
    }
  }

  // ファイル移動
  function move_file(toward, move) {
    var id = get_current_title();
    id = parseInt(id.replace(/comic_/, ''));
    if (toward === "next") {
      id += move;
    } else if (toward === "previous") {
      id -= move;
    } else {
      if (get_current_page() < 2) {
        id -= move;
      } else {
        id += move;
      }
    }
    if (id < 0) {
      id = 0;
    }
    get_page("comic_" + id, 0);
  }

  // ページの取得
  function get_page(id, page) {
    $.ajax({ url: "view.php", data: "id=" + id + "&page=" + page,
      success: function(json) {
        if (json.msg !== "ERROR") {
          set_current_title(id, json.title);
          set_current_page(page);

          paint("#half_page_image", json.files[0]);
          paint("#page_1", json.files[0]);
          paint("#page_2", json.files[1]);
          image_size_reduction();
          $("#index").hide();
        } else {
          move_file("undefined", 1);
        }
      }, error: function(e) {
      }
    });
  }

  // 現在表示している漫画の ID を保存
  function set_current_title(id, title) {
    $("title").text(title);
    $("#current_title").attr("value", id);
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
    var window_height = $(window).height();
    var image_height = window_height;

    $("#viewer img").css({
      "height": image_height + "px",
    });
  }
});

