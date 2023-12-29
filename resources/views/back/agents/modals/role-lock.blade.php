<div class="modal fade modal-style" id="role-lock">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ _i('Lock profile') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p><span class="font-weight-bold text-form">{{ _i('Are you sure you want to block the agent?') }}</span> <span class="username-form">Antonella93</span></p>
                <p>{{ _i('Select the reason to prevent this account from taking any further action.') }}</p>
                <div class="form">
                    <div class="row">
                        <div class="col-12 col-lg-6">
                            <div class="form-group">
                                <label>{{ _i('Reason') }}</label>
                                <select class="form-control">
                                    <option>agentescasino01</option>
                                    <option>tester123</option>
                                    <option>123casino</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-12 col-lg-6"></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-transparent" data-dismiss="modal">
                    {{ _i('Cancel lock') }}
                </button>
                <button type="button" class="btn btn-theme">
                    {{ _i('Ready! Lock') }}
                </button>
            </div>
        </div>
    </div>
</div>
