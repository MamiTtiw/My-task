<?php get_header(); ?>

<div id="app">
  <div class="overflowX">
    <div class="row">
      <div class="col">
        <?php echoBoardTitle("未対応", "secondary", "notouch"); ?>
        <div class="card">
          <div class="card-body">
            <?php echoDraggable("notouch"); ?>
          </div>
        </div>
      </div>
      <div class="col">
        <?php echoBoardTitle("処理中", "primary", "continue"); ?>
        <div class="card">
          <div class="card-body">
            <?php echoDraggable("continue"); ?>
          </div>
        </div>
      </div>
      <div class="col">
        <?php echoBoardTitle("処理済", "info", "confirm"); ?>
        <div class="card">
          <div class="card-body">
            <?php echoDraggable("confirm"); ?>
          </div>
        </div>
      </div>
      <div class="col">
        <?php echoBoardTitle("完了", "dark", "done"); ?>
        <div class="card">
          <div class="card-body">
            <?php echoDraggable("done"); ?>
          </div>
        </div>
      </div>
    </div>
  </div>
  <?php get_template_part("modalAddTask"); ?>
  <?php modalEditTask(); ?>

  <h2 class="memo-title">MEMO</h2>
  <?php echoMemoList(); ?> 
  <?php get_template_part("modalAddMemo"); ?>
  <?php modalEditMemo(); ?>

  <?php echo_add_btn(); ?>

</div><!-- / #app -->

<script src="https://cdn.jsdelivr.net/npm/vue@2/dist/vue.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.8.4/Sortable.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Vue.Draggable/2.23.2/vuedraggable.umd.min.js"></script>
<link rel="stylesheet" href="https://unpkg.com/mavon-editor@2.7.4/dist/css/index.css">
<script src="https://unpkg.com/mavon-editor@2.7.4/dist/mavon-editor.js"></script>
<script>
  const draggable = window['vuedraggable'];
  Vue.use(window['MavonEditor']);
  new Vue({
    el: "#app",
    components: {
      'draggable': draggable,
    },
    data: {
      task: <?php echo getTaskDataConverted(); ?>,
      dragTarget: "",
      droped: "",
      addData:{
        title: "",
        mdEditor: "",
        selectedStatus: "",
        start: "<?php echo date("Y-m-d"); ?>",
        end: "<?php echo date("Y-m-d"); ?>",
        practitioner: "",
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
      editMemoData:{
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
      dragStart(id) {
        this.dragTarget = id;
      },
      touchStart(id) {
        this.dragTarget = id;
      },
      setUpdateTask(id) {
        for (const i in this.task.notouch) {
          if (this.task.notouch[i].id == id) {
            this.droped = "notouch";
          }
        }
        for (const i in this.task.continue) {
          if (this.task.continue[i].id == id) {
            this.droped = "continue";
          }
        }
        for (const i in this.task.confirm) {
          if (this.task.confirm[i].id == id) {
            this.droped = "confirm";
          }
        }
        for (const i in this.task.done) {
          if (this.task.done[i].id == id) {
            console.log(this.task.done[i].id);
            this.droped = "done";
          }
        }
      },
      updateTaskData() {
        this.setUpdateTask(this.dragTarget);
        let ajaxUrl = '<?php echo esc_url(admin_url('admin-ajax.php', __FILE__)); ?>';
        let params = new URLSearchParams();
        params.append('action', 'update_taskData');
        params.append('nonce', '<?php echo wp_create_nonce('update-taskData-nonce'); ?>');
        params.append('task', JSON.stringify(this.task));
        params.append('targetID', this.dragTarget);
        params.append('updateStatus', this.droped);
        const param = {
          method: "POST",
          body: params
        }
        fetch(ajaxUrl, param)
          .then(res => {})
          .catch(error => {
            alert(error + "\n送信失敗");
          });
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
      editMemoModal(i){
        let ajaxUrl = '<?php echo esc_url(admin_url('admin-ajax.php', __FILE__)); ?>';
        let params = new URLSearchParams();
        params.append('action', 'edit_memoReturnData');
        params.append('nonce', '<?php echo wp_create_nonce('edit-memoReturnData-nonce'); ?>');
        params.append('targetId', i);
        const param = {
          method: "POST",
          body: params
        }
				fetch(ajaxUrl, param)
					.then(res => res.json())
					.then(res => {
            this.editMemoData.id          = res.id;
            this.editMemoData.memoTitle   = res.title;
					})
					.catch(error => {
						alert(error + "\n送信失敗");
					});
      },
      editMemoSubmit(){
        let ajaxUrl = '<?php echo esc_url(admin_url('admin-ajax.php', __FILE__)); ?>';
        let params = new URLSearchParams();
        let editMemoForm = this.editMemoData;
        params.append('action', 'edit_memoData');
        params.append('nonce', '<?php echo wp_create_nonce('edit-memoData-nonce'); ?>');
        params.append('editMemoForm', JSON.stringify(editMemoForm));
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
      deleteMemo(i) {
        if (confirm('本当に削除していいですか？')) {
          let ajaxUrl = '<?php echo esc_url(admin_url('admin-ajax.php', __FILE__)); ?>';
          let params = new URLSearchParams();
          params.append('action', 'delete_memoData');
          params.append('nonce', '<?php echo wp_create_nonce('delete-memoData-nonce'); ?>');
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
    },
    watch: {
      task: {
        handler: function(n) {
          this.updateTaskData();
        },
        deep: true,
      },
    },
  });
</script>

<?php get_footer(); ?>