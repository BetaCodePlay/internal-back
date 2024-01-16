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
                    <p><span class="font-weight-bold text-form">{{ _i('¿Qué monto quieres depositar o sacar?') }}</span> <span class="username-form">Antonella93</span></p>
                    <div class="form">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label>{{ _i('Amount') }}</label>
                                    <input type="text" class="form-control" placeholder="" id="userBalanceAmount">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6 g-pr-3">
                                <div class="form-group">
                                    <button type="button" class="btn btn-theme btn-block balanceUser" data-route="{{ route('agents.role.balance-adjustment') }}" data-balance="true">
                                        {{ _i('Deposit') }}
                                    </button>
                                </div>
                            </div>
                            <div class="col-6 g-pl-3">
                                <button type="button" class="btn btn-dark btn-block balanceUser" data-route="{{ route('agents.role.balance-adjustment') }}" data-balance="false">
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
