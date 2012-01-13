var cookie_keys = {
  "half" : "is_half_page_mode",
  "right_click" : "right_click_to_next",
  "right_paginate" : "right_paginate",
  "background_color" : "background_color",
  "current_title" : "current_title",
  "current_page" : "current_page",
  "current_comic_pages" : "current_comic_pages",
}

var keyboard_shortcuts = {
  "37" : "key_down('left')",
  "65" : "key_down('a')",
  "39" : "key_down('right')",
  "68" : "key_down('d')",
}

var preload_images = 4;

$(function(){
  window_resize();
  change_half_mode();
  change_move_mode();
  change_paginate();
  change_background_color();
  get_covers();
  bookmark();

  /*
   * events
   */
  // 単ページ切り替え
  $("#switch_half_page").click(function(){
    var value = is_("half");
    if (value) { set_half_page_mode(false); } else { set_half_page_mode(true); }
    change_half_mode();
    image_size_reduction();
  });

  // ページ移動切り替え
  $("#right_click_to_next").click(function(){
    var checked = $(this).attr('checked');
    if (checked === "checked") {
      set_move_mode(true);
    } else {
      set_move_mode(false);
    }
    change_move_mode();
  });

  // ページ送り切り替え
  $("#right_paginate").click(function(){
    var checked = $(this).attr('checked');
    if (checked === "checked") {
      set_right_paginate(true);
    } else {
      set_right_paginate(false);
    }
    change_paginate();
  });

  // 背景を切り替え
  $("input[name='background_color']").click(function(){
    var checked = $("#background_white").attr("checked");
    if (checked === "checked") {
      set_background_color("white");
    } else {
      set_background_color("black");
    }

    change_background_color();
  });

  // ウィンドウのリサイズを取得
  $(window).resize(function() {
    var is_half_mode = is_("half");
    window_resize();
    change_half_mode();
    image_size_reduction();

    if (is_("half") != is_half_mode) {
      image_size_reduction();
    }
  });

  // Keyboard shortcut.
  $(window).keydown(function(e){
    var _event = keyboard_shortcuts[e.keyCode];
    if (_event != null) { eval(_event); }
  });

  // 蔵書一覧、設定 ON / OFF
  $("#paint_index, #paint_settings, #paint_help").click(function(){
    var _id = $(this).attr("id");
    var target_offset = $('#' + _id).offset().top;
    var paint = null;

    if (_id === "paint_settings") {
      paint = $("#settings");
      $("#index").hide();
      $("#help").hide();
    } else if (_id === "paint_index") {
      paint = $("#index");
      $("#settings").hide();
      $("#help").hide();
    } else {
      paint = $("#help");
      $("#index").hide();
      $("#settings").hide();
    }
    $(".selected").removeClass("selected");

    if (paint.css('display') == 'none') {
      paint.animate({ height: "show" }, "fast");
      $(this).addClass("selected");
    } else {
      paint.animate({ height: "hide" }, "fast");
      $(this).removeClass("selected");
    }

    $('body').animate({scrollTop: target_offset}, 300);
  });

  // 蔵書一覧からクリック
  $(".comic_title").click(function(){
    var _this = $(this);
    var id = _this.attr("id");

    // 描画
    $("#index, #settings").animate({ height: "hide" }, "fast");
    $("#menu a").removeClass("controller");
    $(".selected").removeClass("selected");

    if (is_("half")) {
      $("#half_page").animate({ height: "show" }, "slow");
    } else {
      $("#full_page").animate({ height: "show" }, "slow");
    }
    get_page(id, 1);
  });

  // 次のページ表示
  $(".next").click(function(){ move_page("next", 1); });
  // 前のページ表示
  $(".previous").click(function(){ move_page("previous", 1); });

  $("#page_1").click(function(){
    move_page_1();
  });

  $("#page_2").click(function(){
    move_page_2();
  });

  // 次のファイルへ
  $(".next_file").click(function(){ move_file("next", 1); });

  // 前のファイルへ
  $(".previous_file").click(function(){ move_file("previous", 1); });

  // スライダを動かした
  $("#slider_controller").change(function(event) {
    if (get_current_page() < $(this).val()) {
      move_page("next", 1);
    } else {
      move_page("previous", 1);
    }
  });

  // ページ数を変更した
  $("#current_page").change(function(event) {
    var current_page = get_current_page();
    var move = $(this).val();
      
    if (current_page < move) {
      move_page("next", move - current_page);
    } else if (move < current_page) {
      move_page("previous", current_page - move);
    }
  });

  /*
   * functions
   */
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
    $.cookie(cookie_keys["half"], new_value);
  }

  function is_(item) {
    var mode = $.cookie(cookie_keys[item]);

    if (mode === null) {
      return false;
    } else {
      if (mode == 'true') { return true; } else { return false; }
    }
  }

  function change_half_mode() {
    if (is_("half")) {
      // 単ページに切り替え
      $("#full_page").hide();
      $("#half_page").show();
    } else {
      // 複数ページに切り替え
      $("#full_page").show();
      $("#half_page").hide();
    }
  }

  // ページ移動
  function move_page(toward, move) {
    var id = get_current_title();
    if (toward === "next") {
      var page = get_current_page() + move;
    } else if(toward === "previous") {
      var page = get_current_page() - move;
    }

    if (page < 1) {
      page = 1;
      move_file("previous", 1);
    } else if (page > get_current_comic_pages()){
      page = 1;
      move_file("next", 1);
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
    if (id < 1) {
      id = 1;
    }
    get_page("comic_" + id, 1);
  }

  // ページの取得
  function get_page(id, page) {
    $.ajax({ url: "view.php", data: "id=" + id + "&page=" + page,
      success: function(json) {
        if (json.msg !== "ERROR") {

          set_current_title(id, json.title);
          set_current_page(page);
          set_current_comic_pages(json.pages);

          paint("#half_page_image", json.files[0]);
          paint("#page_1", json.files[0]);
          paint("#page_2", json.files[1]);

          var load_img = new Array();
          for (var i = 0; i < preload_images; i++) {
            load_img[i] = json.files[i+2];
          }
          preload(load_img);

          image_size_reduction();
          set_paginate_ui();

          $(".comic_title").removeClass("reading");
          $("#" + id).addClass("reading");

        } else {
          move_file("undefined", 1);
        }
      }, error: function(e) {
      }
    });
  }

  // ページ送りの UI を操作
  function set_paginate_ui() {
    var current_page = get_current_page();
    var current_comic_pages = get_current_comic_pages();
    $("#all_page").text(current_comic_pages);
    $("#slider_controller").attr("max", current_comic_pages);
    $("#slider_controller").attr("value", current_page);
    $("#current_page").attr("max", current_comic_pages);
    $("#current_page").attr("value", current_page);
  }

  // 現在表示している漫画の ID を保存
  function set_current_title(id, title) {
    $("title").text(title);
    $.cookie(cookie_keys["current_title"], id);
  }

  // 現在表示している漫画の ID を取得
  function get_current_title() {
    return $.cookie(cookie_keys["current_title"]);
  }

  // 現在表示している漫画のページを保存
  function set_current_page(page) {
    $.cookie(cookie_keys["current_page"], page);
  }

  // 現在表示している漫画のページを取得
  function get_current_page() {
    return parseInt($.cookie(cookie_keys["current_page"]));
  }

  // 現在表示している漫画のページ数を保存
  function set_current_comic_pages(pages) {
    $.cookie(cookie_keys["current_comic_pages"], pages);
  }

  // 現在表示している漫画のページ数を取得
  function get_current_comic_pages() {
    return $.cookie(cookie_keys["current_comic_pages"]);
  }

  // 画像描画
  function paint(id, path) {
    $(id).html('<img src="' + path + '" alt="" />');
  }

  // 画像縮小
  function image_size_reduction(id) {
    var _img = $("#viewer img");
    var _window = $(window);

    var window_height = _window.height();
    var window_width  = _window.width();

    var img_width  = _img.width();
    var img_height = _img.height();
    var rate = 1.4;

    if (img_height < 1 || img_width < 1) {
    } else if (img_height < img_width) {
      rate = img_width / img_height;
    } else {
      rate = img_height / img_width;
    }
    if (rate < 1) { rate = 1.4; }

    img_width = window_height / rate;
    var new_img_width = window_width / 2;

    if (new_img_width < img_width && !is_("half")) {
      _img.css({
        "height": "",
        "max-width": new_img_width + "px",
        "max-height": "",
      });
    } else if (is_("half")) {
      _img.css({
        "max-width": window_width + "px",
        "max-height": window_height + "px",
      });
    } else {
      _img.css({
        "height": window_height + "px",
        "max-width": "",
      });
    }
  }

  // ページ移動変更
  function change_move_mode() {
    if (is_("right_click")) {
      $(".left_control").removeClass("next_control").addClass("previous_control");
      $(".right_control").removeClass("previous_control").addClass("next_control");
    } else {
      $(".left_control").removeClass("previous_control").addClass("next_control");
      $(".right_control").removeClass("next_control").addClass("previous_control");
    }
  }

  function set_move_mode(to_right_click) {
    if (to_right_click === true) { var new_value = 'true'; } else { var new_value = 'false'; }
    $.cookie(cookie_keys["right_click"], new_value);
  }

  // ページ送り変更
  function change_paginate() {
    if (is_("right_paginate")) {
      $("#page_1").removeClass("right_page").addClass("left_page");
      $("#page_2").removeClass("left_page").addClass("right_page");
    } else {
      $("#page_1").removeClass("left_page").addClass("right_page");
      $("#page_2").removeClass("right_page").addClass("left_page");
    }
  }

  function set_right_paginate(to_right) {
    if (to_right === true) { var new_value = 'true'; } else { var new_value = 'false'; }
    $.cookie(cookie_keys["right_paginate"], new_value);
  }

  // 背景チェンジ
  function change_background_color() {
    // cookie 名と値の組み合わせが変だけど
    if (is_("background_color")) {
      $("#viewer").css({"background-color":"#333"});
    } else {
      $("#viewer").css({"background-color":"#fff"});
    }
  }

  function set_background_color(color) {
    if (color === "white") { var new_value = 'false'; } else { var new_value = 'true'; }
    $.cookie(cookie_keys["background_color"], new_value);
  }

  // キーボードショートカット
  function key_down(key) {
    if (key === "a" || key === "left") {
      move_page_2();
    } else if (key === "d" || key === "right") {
      move_page_1();
    }
  }

  function move_page_1() {
    if (is_("right_paginate")) {
      if (is_("right_click")) {
        move_page("previous", 2);
      } else {
        move_page("next", 2);
      }
    } else {
      if (is_("right_click")) {
        move_page("next", 2);
      } else {
        move_page("previous", 2);
      }
    }
  }

  function move_page_2() {
    if (is_("right_paginate")) {
      if (is_("right_click")) {
        move_page("next", 2);
      } else {
        move_page("previous", 2);
      }
    } else {
      if (is_("right_click")) {
        move_page("previous", 2);
      } else {
        move_page("next", 2);
      }
    }
  }

  // 表紙を表示
  function get_covers() {
    $.ajax({ 
      url: "thumbnails.php", 
      success: function(json) {
        console.log(json[0]);
        $.each(json, function() {
          var _this = this;
          if (_this.data !== "") {
            $("#comic_" + _this.id).html('<img src="' + _this.data + '" />');
          }
        });
      }, error: function(e) {
        //console.error(e);
      }
    });
  }

  // しおり
  function bookmark() {
    var current_title = get_current_title();
    var current_page = get_current_page();
    if (current_title && current_page) {
      $("#menu a").removeClass("controller");
      $(".selected").removeClass("selected");

      if (is_("half")) {
        $("#half_page").animate({ height: "show" }, "slow");
      } else {
        $("#full_page").animate({ height: "show" }, "slow");
      }
      get_page(current_title, current_page);
    }
  }

});

function preload(imgs){
  for (var i = 0; i < imgs.length; i++) {
    var imgObj = new Image();
    imgObj.src = imgs[i];
  }
}
