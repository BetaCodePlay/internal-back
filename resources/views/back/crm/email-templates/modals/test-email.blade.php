<div class="modal fade" id="test-mail-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('email-templates.test-email') }}" method="post" id="test-email-form">
                <div class="modal-header">
                    <h5 class="modal-title">{{ _i('Test email') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="email">{{ _i('Email') }}</label>
                        <input type="email" class="form-control" name="email" id="email" autocomplete="off">
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="template_id" id="template_id">
                    <button type="button" class="btn u-btn-primary u-btn-3d" id="test-button" data-loading-text="<i class='fa fa-spin fa-spinner'></i> {{ _i('Testing...') }}">
                        <i class="hs-admin-email"></i>
                        {{ _i('Send') }}
                    </button>
                    <button type="button" class="btn u-btn-3d u-btn-bluegray u-btn-3d" data-dismiss="modal">
                        {{ _i('Close') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php
