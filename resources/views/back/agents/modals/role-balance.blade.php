<div class="modal fade modal-style" id="role-balance">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ _i('Balance adjustment') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="modal-body-mini">
                    <p><span class="font-weight-bold text-form">{{ _i('¿Qué monto quieres depositar o sacar?') }}</span> <span class="username-form"></span></p>
                    <div class="form">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label>{{ _i('Amount') }}</label>
                                    <input type="text" class="d-none" placeholder="" id="userBalanceAmount">
                                    <div class="wrap-input">
                                        <input type="text" class="form-control" placeholder="" id="userBalanceAmountGet">
                                        <div class="wrap-element">
                                            <div class="wrap-element-text">{{ session('currency') == 'VEF' ? $free_currency->currency_name : session('currency') }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6 g-pr-3">
                                <div class="form-group">
                                    <button type="button" class="btn btn-theme btn-block balanceUser"
                                            data-route="{{ route('agents.perform-transactions') }}"
                                            data-balance="true"
                                            data-loading-text="<i class='fa fa-spin fa-spinner'></i> {{ _i('Please wait') }}...">
                                        {{ _i('Deposit') }}
                                    </button>
                                </div>
                            </div>
                            <div class="col-6 g-pl-3">
                                <button type="button" class="btn btn-dark btn-block balanceUser"
                                        data-route="{{ route('agents.perform-transactions') }}"
                                        data-balance="false"
                                        data-loading-text="<i class='fa fa-spin fa-spinner'></i> {{ _i('Please wait') }}...">
                                    {{ _i('Withdraw') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
