<div class="modal fade" id="update-limit">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('betpay.clients.accounts.payment-limits.update-limit') }}" method="post" id="update-limit-form">
                <div class="modal-header">
                    <h5 class="modal-title">{{ _i('Update Limit') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12 col-sm-4">
                            <div class="form-group">
                                <label for="status">{{ _i('Status') }}</label>
                                <select name="status" id="status" class="form-control">
                                    <option value="true">{{ _i('Active') }}</option>
                                    <option value="false">{{ _i('Inactive') }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-12 col-sm-4">
                            <div class="form-group">
                                <label for="min">{{ _i('Min') }}</label>
                                <input type="number" name="min" id="min" class="form-control">
                            </div>
                        </div>
                        <div class="col-12 col-sm-4">
                            <div class="form-group">
                                <label for="max">{{ _i('Max') }}</label>
                                <input type="number" name="max" id="max" class="form-control">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="transaction-type" id="transaction_type">
                    <button type="button" class="btn u-btn-primary u-btn-3d" id="update" data-loading-text="<i class='fa fa-spin fa-spinner'></i> {{ _i('Please wait...') }}">
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
