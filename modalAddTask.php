<div
  class="modal fade"
  id="addTaskModal"
  tabindex="-1"
  aria-labelledby="addTaskModalLabel"
  aria-hidden="true"
  >
  <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addTaskModalLabel">タスク追加</h5>
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
          <input type="text" v-model="addData.title" name="title" class="form-control">

          <div class="mt-4 mb-2">
            <i class="fas fa-circle text-primary"></i>
            <strong>内容</strong>
          </div>
          <mavon-editor
            :language="'ja'"
            v-model="addData.mdEditor"
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
                <input class="form-check-input" type="radio" name="taskStatus" id="taskStatus1" value="notouch" v-model="addData.selectedStatus">
                <label class="form-check-label" for="taskStatus1">
                  未対応
                </label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="taskStatus" id="taskStatus2" value="continue" v-model="addData.selectedStatus">
                <label class="form-check-label" for="taskStatus2">
                  処理中
                </label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="taskStatus" id="taskStatus3" value="confirm" v-model="addData.selectedStatus">
                <label class="form-check-label" for="taskStatus3">
                  処理済
                </label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="taskStatus" id="taskStatus4" value="done" v-model="addData.selectedStatus">
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
              <input type="date" name="start_key" v-model="addData.start" class="form-control">
              <div class="mt-4 mb-2">
                <i class="fas fa-circle text-primary"></i>
                <strong>終了日</strong>
              </div>
              <input type="date" name="end_key" v-model="addData.end" class="form-control">
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">キャンセル</button>
        <button type="button" class="btn btn-primary" @click="addSubmit()">追加する</button>
      </div>
    </div>
  </div>
</div>