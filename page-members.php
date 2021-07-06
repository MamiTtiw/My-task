<?php get_header(); ?>

<div id="app">
	<?php
  		$users = get_users();
		foreach ($users as $k => $v) {
			echoUserTask($v->ID, $v->display_name);
		}
	?>
  <?php modalEditTask(); ?>

  <?php get_template_part("modalAddTask"); ?>
  <?php get_template_part("modalAddMemo"); ?>
  <?php echo_add_btn(); ?>
</div>


<script src="https://cdn.jsdelivr.net/npm/vue@2/dist/vue.js"></script>
<link rel="stylesheet" href="https://unpkg.com/mavon-editor@2.7.4/dist/css/index.css">
<script src="https://unpkg.com/mavon-editor@2.7.4/dist/mavon-editor.js"></script>
<script>
  const draggable = window['vuedraggable'];
  Vue.use(window['MavonEditor']);
  new Vue({
    el: "#app",
    data: {
      addData:{
        title: "",
        mdEditor: "",
        selectedStatus: "",
        start: "<?php echo date("Y-m-d"); ?>",
        end: "<?php echo date("Y-m-d"); ?>",
      },
      editData:{
        title: "",
        mdEditor: "",
        selectedStatus: "",
        start: "",
        end: "",
      },
      addMemoData:{
        memoTitle: "",
      },
      mavonEditor: {
        toolbars: {
          bold: true,
          italic: false,
          header: true,
          underline: false,
          strikethrough: false,
          mark: false,
          superscript: false,
          subscript: false,
          quote: false,
          ol: true,
          ul: true,
          link: true,
          code: true,
          table: true,
          fullscreen: true,
          readmodel: false,
          htmlcode: false,
          help: false,
          undo: false,
          redo: false,
          navigation: false,
          alignleft: false,
          aligncenter: false,
          alignright: false,
          subfield: false,
          preview: true,
        },
      },
    },
    methods: {
      editModal(i){
        let ajaxUrl = '<?php echo esc_url(admin_url('admin-ajax.php', __FILE__)); ?>';
        let params = new URLSearchParams();
        params.append('action', 'edit_taskReturnData');
        params.append('nonce', '<?php echo wp_create_nonce('edit-taskReturnData-nonce'); ?>');
        params.append('targetId', i);
        const param = {
          method: "POST",
          body: params
        }
				fetch(ajaxUrl, param)
					.then(res => res.json())
					.then(res => {
            this.editData.id             = res.id;
            this.editData.title          = res.title;
            this.editData.mdEditor       = res.content;
            this.editData.selectedStatus = res.selectedStatus;
            this.editData.start          = res.start;
            this.editData.end            = res.end;
					})
					.catch(error => {
						alert(error + "\n送信失敗");
					});
      },
      editSubmit(){
        let ajaxUrl = '<?php echo esc_url(admin_url('admin-ajax.php', __FILE__)); ?>';
        let params = new URLSearchParams();
        let editTaskForm = this.editData;
        params.append('action', 'edit_taskData');
        params.append('nonce', '<?php echo wp_create_nonce('edit-taskData-nonce'); ?>');
        params.append('editTaskForm', JSON.stringify(editTaskForm));
        const param = {
          method: "POST",
          body: params
        }
				fetch(ajaxUrl, param)
					.then(res => res.json())
					.then(res => {
            location.reload();
					})
					.catch(error => {
						alert(error + "\n送信失敗");
					});
      },
      addSubmit(){
        let ajaxUrl = '<?php echo esc_url(admin_url('admin-ajax.php', __FILE__)); ?>';
        let params = new URLSearchParams();
        let addTaskForm = this.addData;
        params.append('action', 'insert_taskData');
        params.append('nonce', '<?php echo wp_create_nonce('insert-taskData-nonce'); ?>');
        params.append('addTaskForm', JSON.stringify(addTaskForm));
        const param = {
          method: "POST",
          body: params
        }
				fetch(ajaxUrl, param)
					.then(res => res.text())
					.then(res => {
            location.reload();
					})
					.catch(error => {
						alert(error + "\n送信失敗");
					});
      },
      deleteTask(i) {
        if (confirm('本当に削除していいですか？')) {
          let ajaxUrl = '<?php echo esc_url(admin_url('admin-ajax.php', __FILE__)); ?>';
          let params = new URLSearchParams();
          params.append('action', 'delete_taskData');
          params.append('nonce', '<?php echo wp_create_nonce('delete-taskData-nonce'); ?>');
          params.append('targetId', i);
          const param = {
            method: "POST",
            body: params
          }
          fetch(ajaxUrl, param)
            .then(res => res.json())
            .then(res => {
              location.reload();
            })
            .catch(error => {
              alert(error + "\n送信失敗");
            });
        }
      },
      doDelete(t, i) {
        if (confirm('本当に削除していいですか？')) {
          this.task[t].splice(i, 1);
        }
      },
      doSessionClear: function() {
        location.reload();
      },
      addMemoSubmit(){
        let ajaxUrl = '<?php echo esc_url(admin_url('admin-ajax.php', __FILE__)); ?>';
        let params = new URLSearchParams();
        let addMemoForm = this.addMemoData;
        params.append('action', 'insert_memoData');
        params.append('nonce', '<?php echo wp_create_nonce('insert-memoData-nonce'); ?>');
        params.append('addMemoForm', JSON.stringify(addMemoForm));
        const param = {
          method: "POST",
          body: params
        }
				fetch(ajaxUrl, param)
					.then(res => res.text())
					.then(res => {
            location.reload();
					})
					.catch(error => {
						alert(error + "\n送信失敗");
					});
      },
    },
  });
</script>

<?php get_footer(); ?>