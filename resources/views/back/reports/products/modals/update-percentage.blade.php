<div class="modal fade" id="update-percentage">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('configurations.credentials.update-percentage') }}" method="post" id="update-percentage-form">
                <div class="modal-header">
                    <h5 class="modal-title">{{ _i('Update percentage') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="amount">{{ _i('Percentage') }}</label>
                        <input type="number" class="form-control" name="percentage" id="percentage">
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="credential" id="credential">
                    <input type="hidden" name="provider" class="provider">
                    <input type="hidden" name="currency" class="currency">
                    <button type="button" class="btn u-btn-primary u-btn-3d" id="update-button" data-loading-text="<i class='fa fa-spin fa-spinner'></i> {{ _i('Please wait...') }}">
                        {{ _i('Update') }}
                    </button>
                    <button type="button" class="btn u-btn-3d u-btn-bluegray u-btn-3d" data-dismiss="modal">
                        {{ _i('Close') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
