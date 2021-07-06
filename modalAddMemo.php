<div
  class="modal fade"
  id="addMemoModal"
  tabindex="-1"
  aria-labelledby="addMemoModalLabel"
  aria-hidden="true"
  >
  <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addMemoModalLabel">メモ追加</h5>
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
          <input type="text" v-model="addMemoData.memoTitle" name="title" class="form-control">
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">キャンセル</button>
        <button type="button" class="btn btn-primary" @click="addMemoSubmit()">追加する</button>
      </div>
    </div>
  </div>
</div>