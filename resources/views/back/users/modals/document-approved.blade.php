<div class="modal fade" id="document-approved-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ _i('Verify document') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('users.documents-action') }}" method="post" id="approved-document-form">
                <div class="modal-body">
                    <h6>
                        {{_i('Are you sure to take the action?')  }}
                    </h6>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="type" id="type" value="">
                    <input type="hidden" name="user" id="user" value="">
                    <input type="hidden" name="status" id="status" value="">
                    <input type="hidden" name="document_id" id="document_id" value="">
                    <button type="button" class="btn u-btn-primary u-btn-3d" id="approved" data-loading-text="<i class='fa fa-spin fa-spinner'></i> {{ _i('Please wait...') }}">
                        {{_i('Approve')  }}
                    </button>
                    <button type="button" class="btn u-btn-3d u-btn-bluegray u-btn-3d" data-dismiss="modal">
                        {{ _i('Close') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
