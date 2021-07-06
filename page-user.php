<?php get_header(); ?>

<div id="app">
	<div class="container">
		<?php	if ( have_posts() ) {
			while ( have_posts() ) { the_post(); ?>
				<div><?php the_content(); ?></div>
			<?php }
		}	?>
	</div>
	<?php echoMemoList(); ?>
	<?php modalEditMemo(); ?>
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
      editMemoData:{
        memoTitle: "",
      },
    },
    methods: {
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
  });

</script>

<?php get_footer(); ?>