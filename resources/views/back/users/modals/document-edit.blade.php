<div class="modal fade" id="document-edit-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ _i('Edit document') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('users.documents-edit') }}" method="post" id="edit-document-form" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="form-group">
                        <div class="file-loading">
                            <label for="image">{{ _i('Document') }}</label>
                            <input type="file" name="image" id="image" class="opacity-0">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="user_id_edit" id="user_id_edit" value="">
                    <input type="hidden" name="document_id_edit" id="document_id_edit" value="">
                    <button type="submit"  class="btn u-btn-primary u-btn-3d" id="edit" data-loading-text="<i class='fa fa-spin fa-spinner'></i> {{ _i('Please wait...') }}">
                        {{_i('Edit')  }}
                    </button>
                    <button type="button" class="btn u-btn-3d u-btn-bluegray u-btn-3d" data-dismiss="modal">
                        {{ _i('Close') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
