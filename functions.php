<?php

/**
 * CSS & JS 読み込み
 */
function add_files()
{
  wp_enqueue_style('style-bootstrap', 'https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css', "", '');
  wp_enqueue_style('fontawesome', 'https://use.fontawesome.com/releases/v5.13.0/css/all.css', "", '');
  wp_enqueue_style('main-style', get_template_directory_uri() . '/css/style.css', "", '');
  wp_enqueue_style('responsive-style', get_template_directory_uri() . '/css/responsive.css', "", '');
  wp_enqueue_style('header-style', get_template_directory_uri() . '/css/header.css', "", '');
  wp_enqueue_style('footer-style', get_template_directory_uri() . '/css/footer.css', "", '');
  wp_enqueue_style('single-style', get_template_directory_uri() . '/css/single.css', "", '');
  wp_deregister_script('jquery');
  wp_enqueue_script('jquery', 'https://code.jquery.com/jquery-3.5.1.min.js', "", "", false);
  wp_enqueue_script('popper', 'https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js', array('jquery'), "", true);
  wp_enqueue_script('bootstrap4.5', 'https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js', array('jquery'), '', true);
}
add_action('wp_enqueue_scripts', 'add_files');


/**
 * タイトル設定
 */
add_theme_support('title-tag');
function wp_document_title_parts($title)
{
  if (is_home() || is_front_page()) {
    unset($title['tagline']);
  }
  return $title;
}
add_filter('document_title_parts', 'wp_document_title_parts', 10, 1);

function wp_document_title_separator($separator)
{
  $separator = '|';
  return $separator;
}
add_filter('document_title_separator', 'wp_document_title_separator');


/**
 * OGPタグ
 */
function my_meta_ogp()
{
  if (is_front_page() || is_home() || is_singular()) {
    global $post;
    $ogp_title = '';
    $ogp_descr = '';
    $ogp_url = '';
    $ogp_img = '';
    $insert = '';
    if (is_singular()) {
      setup_postdata($post);
      $ogp_title = $post->post_title;
      // $ogp_descr = mb_substr(get_the_excerpt(), 0, 100);
      $ogp_descr = get_bloginfo('description');
      $ogp_url = get_permalink();
      wp_reset_postdata();
    } elseif (is_front_page() || is_home()) {
      $ogp_title = get_bloginfo('name');
      $ogp_descr = get_bloginfo('description');
      $ogp_url = home_url();
    }
    $ogp_type = (is_front_page() || is_home()) ? 'website' : 'article';
    if (is_singular() && has_post_thumbnail()) {
      $ps_thumb = wp_get_attachment_image_src(get_post_thumbnail_id(), 'full');
      $ogp_img = $ps_thumb[0];
    } else {
      $ogp_img = get_template_directory_uri() . '/img/share/ogimage.jpg';
    }
    $insert .= '<meta property="og:title" content="' . esc_attr($ogp_title) . '" />' . "\n";
    $insert .= '<meta property="og:description" content="' . esc_attr($ogp_descr) . '" />' . "\n";
    $insert .= '<meta property="og:type" content="' . $ogp_type . '" />' . "\n";
    $insert .= '<meta property="og:url" content="' . esc_url($ogp_url) . '" />' . "\n";
    $insert .= '<meta property="og:image" content="' . esc_url($ogp_img) . '" />' . "\n";
    $insert .= '<meta property="og:site_name" content="' . esc_attr(get_bloginfo('name')) . '" />' . "\n";
    // $insert .= '<meta name="twitter:card" content="summary_large_image" />' . "\n";
    // $insert .= '<meta name="twitter:site" content="ツイッターのアカウント名" />' . "\n";
    $insert .= '<meta property="og:locale" content="ja_JP" />' . "\n";
    //facebookのapp_id（設定する場合）
    // $insert .= '<meta property="fb:app_id" content="ここにappIDを入力">' . "\n";
    //app_idを設定しない場合ここまで消す
    echo $insert;
  }
}
add_action('wp_head', 'my_meta_ogp');


/**
 * アイキャッチを有効にする
 */
add_theme_support('post-thumbnails');


/**
 * ダッシュボード(管理画面)に変数menuを表示
 */
function view_adminmenu()
{
  global $menu;
  echo '<pre style="padding-left: 200px;">';
  print_r($menu);
  echo '</pre>';
}
// add_action('admin_menu', 'view_adminmenu');


/**
 * 「投稿」を「MEMO」に変える
 */
function change_post_menu_label()
{
  global $menu;
  global $submenu;
  $menu[5][0] = 'MEMO';
  $submenu['edit.php'][5][0] = 'MEMO一覧';
  $submenu['edit.php'][10][0] = '新しいMEMO';
  $submenu['edit.php'][16][0] = 'タグ';
}
function change_post_object_label()
{
  global $wp_post_types;
  $labels = &$wp_post_types['post']->labels;
  $labels->name = 'MEMO';
  $labels->singular_name = 'MEMO';
  $labels->add_new = _x('追加', 'MEMO');
  $labels->add_new_item = 'MEMOの新規追加';
  $labels->edit_item = 'MEMOの編集';
  $labels->new_item = '新規MEMO';
  $labels->view_item = 'MEMOを表示';
  $labels->search_items = 'MEMOを検索';
  $labels->not_found = 'MEMOが見つかりませんでした';
  $labels->not_found_in_trash = 'ゴミ箱にMEMOは見つかりませんでした';
}
add_action('init', 'change_post_object_label');
add_action('admin_menu', 'change_post_menu_label');


/**
 * ダッシュボードのメニュー順番変更
 */
function my_custom_menu_order($menu_order)
{
  if (!$menu_order) return true;
  return array(
    'index.php', //ダッシュボード
    'separator1', //セパレータ１
    'edit.php', //メモ
    'edit.php?post_type=task', // タスク
    'separator2', //セパレータ２
    'edit.php?post_type=page', //固定ページ
    'upload.php', //メディア
    // 'separator-last',
    // 'edit-comments.php', //コメント
    // 'themes.php', //外観
    // 'plugins.php', //プラグイン
    // 'users.php', //ユーザー
    // 'tools.php', //ツール
    // 'options-general.php', //設定
  );
}
add_filter('custom_menu_order', 'my_custom_menu_order');
add_filter('menu_order', 'my_custom_menu_order');


/**
 * ログイン強制
 */
function my_require_login()
{
  global $pagenow;
  if (
    !is_user_logged_in() &&
    $pagenow !== 'wp-login.php' &&
    $pagenow !== 'index.php' &&
    !(defined('DOING_AJAX') && DOING_AJAX) &&
    !(defined('DOING_CRON') && DOING_CRON)
  ) {
    wp_redirect(home_url().'/login/');
    exit;
  }
}
 add_action( 'init', 'my_require_login' );


/**
 * 管理画面で投稿した人の投稿だけが現れるようにする
 */
function show_only_authorpost($query)
{
  global $current_user;
  if (is_admin()) {
    if (current_user_can('author')) {
      $query->set('author', $current_user->ID);
    }
  }
}
add_action('pre_get_posts', 'show_only_authorpost');


/**
 * ボードのタイトル出力
 */
function echoBoardTitle($t, $c, $s){ ?>
  <div class="mb-2">
    <i class="fas fa-circle text-<?php echo $c; ?>"></i>
    <strong><?php echo $t; ?></strong>
    <span class="badge rounded-pill bg-gray text-dark px-3" v-text="task.<?php echo $s; ?>.length"></span>
  </div>
<?php }


/**
 * ドラッガブル出力
 */
function echoDraggable($s){ ?>
  <draggable tag="ul" class="list-group" :options="{group:'ITEMS', handle:'.handle'}" v-model="task.<?php echo $s; ?>">
    <li v-for="(v, i) in task.<?php echo $s; ?>" :key="i" @touchstart="dragStart(v.id)" @dragstart="dragStart(v.id)" class="list-group-item">
      <div class="d-flex align-items-center">
        <i class="fas fa-grip-vertical text-gray handle mr-3"></i>
        <div>
          <?php if(current_user_can('editor')): ?><div class="mb-2" v-text="v.author"></div><?php endif; ?> 
          <div><b v-text="v.title"></b></div>
          <div :class="{ end_alert: v.alert }">{{ v.end }}</div>
          <div class="mt-1 mb-2">{{ v.content }}</div>
        </div>
      </div>
      <a :href="v.link" class="btn btn-outline-secondary text-nowrap ml-2">詳細</a>
      <button
        @click="editModal(v.id)"
        data-toggle="modal"
        data-target="#taskEditModal"
        class="btn btn-outline-secondary text-nowrap ml-2"
      >
        編集
      </button>
      <button class="btn btn-outline-secondary text-nowrap ml-2" @click="deleteTask(v.id)">削除</button>
    </li>
  </draggable>
<?php }


/**
 * タスクデータをカテゴリごとに変換してjsonエンコードして返す
 */
function getTaskDataConverted(){
  $toDoData        = getToDoData();
  $arr["notouch"]  = [];
  $arr["continue"] = [];
  $arr["confirm"]  = [];
  $arr["done"]     = [];
  foreach ($toDoData as $k => $v) {
    if ($v["status"] === "notouch") {
      $arr["notouch"][] = $v;
    }
    if ($v["status"] === "continue") {
      $arr["continue"][] = $v;
    }
    if ($v["status"] === "confirm") {
      $arr["confirm"][] = $v;
    }
    if ($v["status"] === "done") {
      $arr["done"][] = $v;
    }
  }
  return json_encode($arr);
}


/**
 * toDoデータ取得
 */
function getToDoData(){
  $user = wp_get_current_user()->ID;
  if(current_user_can('editor')){
    $user = '';
  }
  $arr = [];
  $args = array(
    'post_type' => 'task',
    'posts_per_page' => -1,
    'post_status' => 'publish',
    'author'      => $user,
  );
  $the_query = new WP_Query($args);
  if ($the_query->have_posts()) :
    while ($the_query->have_posts()) : $the_query->the_post();
      $id           = get_the_ID();
      $content      = wp_strip_all_tags(get_the_content());
      $title        = get_the_title();
      $status       = get_the_terms($id, "task_status")[0]->slug;
      $link         = get_the_permalink();
      $sort         = empty(get_post_meta($id, "sort", true)) ? 0 : get_post_meta($id, "sort", true);
      $author       = get_the_author();
      $end          = get_post_meta($id, "end_task", true);
      $alert        = false;
      if( !empty($end) ){
        date_default_timezone_set('Asia/Tokyo');
        $now        = date("Y-m-d");
        $future     = strtotime($now) - strtotime($end) >= 0 ? false : true;
        if(!$future){
          $alert    = (strtotime($now) - strtotime($end))/60/60/24 > 3 ? false : true;
        }
      }
      $arr[]        = [
        "id"        => $id,
        "title"     => $title,
        "content"   => $content,
        "status"    => $status,
        "link"      => $link,
        "sort"      => $sort,
        "author"    => $author,
        "end"       => $end,
        "alert"     => $alert,
      ];
      $ids = array_column($arr, 'sort');
      array_multisort($ids, SORT_ASC, $arr);
    endwhile;
  endif;
  return $arr;
}


/**
 * AjaxでのtoDoデータ変更
 */
function update_taskData()
{
  $nonce = $_POST['nonce'];
  if (!wp_verify_nonce($nonce, 'update-taskData-nonce')) {
    echo "nonce error";
    wp_die();
  }
  $task         = !empty($_POST['task']) ? $_POST['task'] : "";
  $taskArr      = json_decode(stripslashes($task));
  $targetID     = !empty($_POST['targetID']) ? $_POST['targetID'] : "";
  $updateStatus = !empty($_POST['updateStatus']) ? $_POST['updateStatus'] : "";
  $target       = get_post($targetID);
  $target       = json_encode($target);
  wp_set_object_terms( $targetID, $updateStatus, "task_status", false );
  foreach ($taskArr as $k => $v) {
    foreach ($v as $k2 => $v2) {
      update_post_meta($v2->id, "sort", $k2);
    }
  }
  if ($res) {
    echo "true";
  } else {
    echo "false";
  }
  wp_die();
}
add_action('wp_ajax_update_taskData', 'update_taskData');
add_action('wp_ajax_nopriv_update_taskData', 'update_taskData');


/**
 * Ajaxでのタスクデータ登録
 */
function insert_taskData()
{
  $nonce = $_POST['nonce'];
  if (!wp_verify_nonce($nonce, 'insert-taskData-nonce')) {
    echo "nonce error";
    wp_die();
  }
  $user = wp_get_current_user();
  $userId = $user->ID;
  $addTaskFormStr = !empty($_POST['addTaskForm']) ? $_POST['addTaskForm'] : "";
  $addTaskFormObj = json_decode(stripslashes($addTaskFormStr));
  $title = $addTaskFormObj->title;
  $contentMd = $addTaskFormObj->mdEditor;
  require_once("parsedown/Parsedown.php");
  $parsedown = new Parsedown();
  $contentHtml = "<!-- wp:html -->" . $parsedown->text($contentMd) . "<!-- /wp:html -->";
  $catObj = get_term_by("slug", $addTaskFormObj->selectedStatus, "task_status");
  $statusId = $catObj->term_id;
  $start = $addTaskFormObj->start;
  $end = $addTaskFormObj->end;
  $my_post = array(
    'post_type'     => 'task',
    'post_title'    => $title,
    'post_content'  => $contentHtml,
    'post_status'   => 'publish',
    'post_author'   => $userId,
   );
   $post_id = wp_insert_post( $my_post );
   add_post_meta($post_id, "start_task", $start);
   add_post_meta($post_id, "end_task", $end);
   wp_set_object_terms($post_id, [$statusId], "task_status");
   if ($post_id) {
     echo "true";
  } else {
    echo "false";
  }
  wp_die();
}
add_action('wp_ajax_insert_taskData', 'insert_taskData');
add_action('wp_ajax_nopriv_insert_taskData', 'insert_taskData');


/**
 * Ajaxでの編集タスクデータ返し
 */
function edit_taskReturnData()
{
  $nonce = $_POST['nonce'];
  if (!wp_verify_nonce($nonce, 'edit-taskReturnData-nonce')) {
    echo "nonce error";
    wp_die();
  }
  $postId = !empty($_POST['targetId']) ? $_POST['targetId'] : "";
  date_default_timezone_set('Asia/Tokyo');
  $data = get_post($postId, 'OBJECT');
  $selectedStatus = get_the_terms($postId, "task_status")[0];
  $start = get_post_meta($postId, "start_task", true);
  $end = get_post_meta($postId, "end_task", true);
  $arr  = [
    "id" => $data->ID,
    "content" => $data->post_content,
    "title" => $data->post_title,
    "selectedStatus" => $selectedStatus->slug,
    "start" => $start,
    "end" => $end,
  ];
  $res = json_encode($arr);
  if ($res) {
     echo $res;
  } else {
    echo "false";
  }
  wp_die();
}
add_action('wp_ajax_edit_taskReturnData', 'edit_taskReturnData');
add_action('wp_ajax_nopriv_edit_taskReturnData', 'edit_taskReturnData');


/**
 * Ajaxでのタスクデータ削除
 */
function delete_taskData()
{
  $nonce = $_POST['nonce'];
  if (!wp_verify_nonce($nonce, 'delete-taskData-nonce')) {
    echo "nonce error";
    wp_die();
  }
  $targetId = !empty($_POST['targetId']) ? $_POST['targetId'] : "";
  $res = wp_delete_post($targetId);
  if ($res) {
    echo "true";
  } else {
    echo "false";
  }
  wp_die();
}
add_action('wp_ajax_delete_taskData', 'delete_taskData');
add_action('wp_ajax_nopriv_delete_taskData', 'delete_taskData');


/**
 * モーダルからのタスク編集
 */
 function modalEditTask(){
  $title = "タスク編集";
  $submit = "editSubmit()";
  $submitName = "編集する"; ?>
  <div
    class="modal fade"
    id="taskEditModal"
    tabindex="-1"
    aria-labelledby="taskEditModalLabel"
    aria-hidden="true"
  >
  <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="taskEditModalLabel"><?php echo $title; ?></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form name="addTaskForm">

          <div class="mb-2">
            <i class="fas fa-circle text-primary"></i>
            <strong>タイトル</strong>
          </div>
          <input type="text" v-model="editData.title" name="title" class="form-control">

          <div class="mt-4 mb-2">
            <i class="fas fa-circle text-primary"></i>
            <strong>内容</strong>
          </div>
          <mavon-editor
            :language="'ja'"
            v-model="editData.mdEditor"
            placeholder="Markdown記入！"
            :toolbars="mavonEditor.toolbars"
          >
          </mavon-editor>

          <div class="row row-cols-1 row-cols-sm-2 mt-4">
            <div class="col">
              <div class="mb-2">
                <i class="fas fa-circle text-primary"></i>
                <strong>ステータス</strong>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="taskStatus" id="taskStatus1" value="notouch" v-model="editData.selectedStatus">
                <label class="form-check-label" for="taskStatus1">
                  未対応
                </label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="taskStatus" id="taskStatus2" value="continue" v-model="editData.selectedStatus">
                <label class="form-check-label" for="taskStatus2">
                  処理中
                </label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="taskStatus" id="taskStatus3" value="confirm" v-model="editData.selectedStatus">
                <label class="form-check-label" for="taskStatus3">
                  処理済み
                </label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="taskStatus" id="taskStatus4" value="done" v-model="editData.selectedStatus">
                <label class="form-check-label" for="taskStatus4">
                  完了
                </label>
              </div>
            </div>
            <div class="col">
              <div class="mb-2">
                <i class="fas fa-circle text-primary"></i>
                <strong>開始日</strong>
              </div>
              <input type="date" name="start_key" v-model="editData.start" class="form-control">
              <div class="mt-4 mb-2">
                <i class="fas fa-circle text-primary"></i>
                <strong>終了日</strong>
              </div>
              <input type="date" name="end_key" v-model="editData.end" class="form-control">
            </div>
          </div>

        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">キャンセル</button>
        <button type="button" class="btn btn-primary" @click="<?php echo $submit; ?>"><?php echo $submitName; ?></button>
      </div>
    </div>
  </div>
</div>
<?php }


/**
 * Ajaxでのタスクデータ編集
 */
function edit_taskData()
{
  $nonce = $_POST['nonce'];
  if (!wp_verify_nonce($nonce, 'edit-taskData-nonce')) {
    echo "nonce error";
    wp_die();
  }
  $editTaskFormStr = !empty($_POST['editTaskForm']) ? $_POST['editTaskForm'] : "";
  $editTaskFormObj = json_decode(stripslashes($editTaskFormStr));
  $targetId = $editTaskFormObj->id;
  $title = $editTaskFormObj->title;
  $contentMd = $editTaskFormObj->mdEditor;
  require_once("parsedown/Parsedown.php");
  $parsedown = new Parsedown();
  $contentHtml = $parsedown->text($contentMd);
  $status = $editTaskFormObj->selectedStatus;
  $start = $editTaskFormObj->start;
  $end = $editTaskFormObj->end;
  $my_post = array(
    'ID'            => $targetId,
    'post_title'    => $title,
    'post_content'  => $contentHtml,
  );
  $post_id = wp_update_post($my_post, false);
  update_post_meta($post_id, "start_task", $start);
  update_post_meta($post_id, "end_task", $end);
  wp_set_object_terms( $post_id, $status, "task_status", false );
  if ($post_id) {
    echo "true";
  } else {
    echo "false";
  }
  wp_die();
}
add_action('wp_ajax_edit_taskData', 'edit_taskData');
add_action('wp_ajax_nopriv_edit_taskData', 'edit_taskData');


/**
 * MEMO出力
 */
function echoMemoList()
{
  $user = wp_get_current_user();
  $id   = get_the_ID();
  $args = array(
    'post_type' => 'post',
    'posts_per_page' => -1,
    'post_status' => 'publish',
    'author'      => $user->ID,
    "id"        => $id,
  );
  $the_query = new WP_Query($args);
  if ($the_query->have_posts()) : ?>
    <div class="memo-lists">
      <div class="row row-cols-3">
        <?php while ($the_query->have_posts()) : $the_query->the_post(); ?>
          <div class="col mb-1">
            <div class="card" >
              <div class="card-body">
                <div class="memo-contents">
                    <?php the_title(); ?>
                </div>
                <button
                    @click="editMemoModal(<?php the_ID(); ?>)"
                    data-toggle="modal"
                    data-target="#memoEditModal"
                    class="btn btn-outline-secondary text-nowrap mt-3 ml-2"
                >
                編集
                </button>
                <button class="btn btn-outline-secondary text-nowrap mt-3 ml-2" @click="deleteMemo(<?php the_ID(); ?>)">削除</button>
              </div>
            </div>
          </div>
        <?php endwhile; ?>
      </div>
    </div>
<?php endif;
  wp_reset_postdata();
}


/**
 * Ajaxでのメモデータ登録
 */
function insert_memoData()
{
  $nonce = $_POST['nonce'];
  if (!wp_verify_nonce($nonce, 'insert-memoData-nonce')) {
    echo "nonce error";
    wp_die();
  }
  $user = wp_get_current_user();
  $userId = $user->ID;
  $addMemoFormStr = !empty($_POST['addMemoForm']) ? $_POST['addMemoForm'] : "";
  $addMemoFormObj = json_decode(stripslashes($addMemoFormStr));
  $title = $addMemoFormObj->memoTitle;
  $my_post = array(
    'post_type'     => 'post',
    'post_status' => 'publish',
    'post_title'    => $title,
    'post_author'   => $userId,
   );
   $post_id = wp_insert_post( $my_post );
   if ($post_id) {
     echo "true";
  } else {
    echo "false";
  }
  wp_die();
}
add_action('wp_ajax_insert_memoData', 'insert_memoData');
add_action('wp_ajax_nopriv_insert_memoData', 'insert_memoData');


/**
 * Ajaxでの編集メモデータ返し
 */
function edit_memoReturnData()
{
  $nonce = $_POST['nonce'];
  if (!wp_verify_nonce($nonce, 'edit-memoReturnData-nonce')) {
    echo "nonce error";
    wp_die();
  }
  $postId = !empty($_POST['targetId']) ? $_POST['targetId'] : "";
  date_default_timezone_set('Asia/Tokyo');
  $data = get_post($postId, 'OBJECT');
  $arr  = [
    "id" => $data->ID,
    "title" => $data->post_title,
  ];
  $res = json_encode($arr);
  if ($res) {
     echo $res;
  } else {
    echo "false";
  }
  wp_die();
}
add_action('wp_ajax_edit_memoReturnData', 'edit_memoReturnData');
add_action('wp_ajax_nopriv_edit_memoReturnData', 'edit_memoReturnData');


/**
 * Ajaxでのメモデータ編集
 */
function edit_memoData()
{
  $nonce = $_POST['nonce'];
  if (!wp_verify_nonce($nonce, 'edit-memoData-nonce')) {
    echo "nonce error";
    wp_die();
  }
  $editMemoFormStr = !empty($_POST['editMemoForm']) ? $_POST['editMemoForm'] : "";
  $editMemoFormObj = json_decode(stripslashes($editMemoFormStr));
  $targetId = $editMemoFormObj->id;
  $title = $editMemoFormObj->memoTitle;
  $my_post = array(
    'ID'            => $targetId,
    'post_title'    => $title,
  );
  $post_id = wp_update_post($my_post, false);
  if ($post_id) {
    echo "true";
  } else {
    echo "false";
  }
  wp_die();
}
add_action('wp_ajax_edit_memoData', 'edit_memoData');
add_action('wp_ajax_nopriv_edit_memoData', 'edit_memoData');


/**
 * モーダルからのメモ編集
 */
function modalEditMemo(){
  $title = "メモ編集";
  $submit = "editMemoSubmit()";
  $submitName = "編集する"; ?>
  <div
    class="modal fade"
    id="memoEditModal"
    tabindex="-1"
    aria-labelledby="memoEditModalLabel"
    aria-hidden="true"
  >
  <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="memoEditModalLabel"><?php echo $title; ?></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form name="addMemoForm">
          <div class="mb-2">
            <i class="fas fa-circle text-primary"></i>
            <strong>内容</strong>
          </div>
          <input type="text" v-model="editMemoData.memoTitle" name="title" class="form-control">
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">キャンセル</button>
        <button type="button" class="btn btn-primary" @click="<?php echo $submit; ?>"><?php echo $submitName; ?></button>
      </div>
    </div>
  </div>
</div>
<?php }


/**
 * Ajaxでのメモデータ削除
 */
function delete_memoData()
{
  $nonce = $_POST['nonce'];
  if (!wp_verify_nonce($nonce, 'delete-memoData-nonce')) {
    echo "nonce error";
    wp_die();
  }
  $targetId = !empty($_POST['targetId']) ? $_POST['targetId'] : "";
  $res = wp_delete_post($targetId);
  if ($res) {
    echo "true";
  } else {
    echo "false";
  }
  wp_die();
}
add_action('wp_ajax_delete_memoData', 'delete_memoData');
add_action('wp_ajax_nopriv_delete_memoData', 'delete_memoData');


/**
 * 追加ボタン
 */
function echo_add_btn(){ ?>
  <div class="addBtns">
    <button
      class = "btn btn-secondary btn-lg mr-3"
      data-toggle = "modal"
      data-target = "#addTaskModal"
    >＋タスク
    </button>
    <button
      class = "btn btn-secondary btn-lg mr-3"
      data-toggle = "modal"
      data-target = "#addMemoModal"
    >＋メモ
    </button>
  </div>
<?php }


/**
 * ユーザーごとのタスク一覧
 */
function echoUserTask($id, $name)
{
  $args = array(
    'post_type' => 'task',
    'posts_per_page' => -1,
    'post_status' => 'publish',
    'author' => $id,
  );
  $the_query = new WP_Query($args); ?>
  <h3 class="userName mt-4 pl-4"><?php echo $name; ?></h3>
    <?php if ($the_query->have_posts()): ?>
      <section class="usertask">
        <div class="row row-cols-4">
            <?php while ($the_query->have_posts()) : $the_query->the_post(); ?>
                <div class="col">
                  <div class="card">
                    <div class="card-header">
                        <?php
                          $terms = get_the_terms(get_the_ID(), "task_status")[0];
                          $status = $terms->name;
                        ?>
                        <div>ステータス：<?php echo $status; ?></div>
					              <div>終了日：<?php echo get_post_meta(get_the_ID(), "end_task")[0]; ?></div>
                    </div>
                    <div class="card-body">
                        <h5><?php the_title(); ?></h5>
                        <div><?php the_content(); ?></div>
                    </div>
                    <button
			                @click="editModal(<?php the_ID(); ?>)"
			                data-toggle="modal"
			                data-target="#taskEditModal"
			                class="btn btn-dark text-nowrap  mr-4 mb-3 ml-4"
	                  >
		                編集
		                </button>
                  </div>
                </div>
            <?php endwhile; 
          wp_reset_postdata(); ?>
       </div>
     </section>
    <?php endif; 
 } 

