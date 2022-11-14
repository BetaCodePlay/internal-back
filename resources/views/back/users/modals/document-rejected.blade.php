<div class="modal fade" id="document-rejected-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ _i('Rejected document') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('users.documents-action') }}" method="post" id="rejected-document-form">
                <div class="modal-body">
                    <h6>
                        {{_i('Are you sure to take the action?')  }}
                    </h6>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="type_id" id="type_id" value="">
                    <input type="hidden" name="user_id" id="user_id" value="">
                    <input type="hidden" name="status_id" id="status_id" value="">
                    <input type="hidden" name="id_document" id="id_document" value="">
                    <input type="hidden" name="file_document" id="file_document" value="">
                    <button type="button" class="btn u-btn-primary u-btn-3d" id="rejected" data-loading-text="<i class='fa fa-spin fa-spinner'></i> {{ _i('Please wait...') }}">
                        {{_i('Delete')  }}
                    </button>
                    <button type="button" class="btn u-btn-3d u-btn-bluegray u-btn-3d" data-dismiss="modal">
                        {{ _i('Close') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
