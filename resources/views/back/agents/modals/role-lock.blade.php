<div class="modal fade modal-style" id="role-lock">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p><span class="font-weight-bold text-form">{{ _i('Are you sure you want to block the agent?') }}</span> <span class="username-form"></span></p>
                <p>{{ _i('Select the reason to prevent this account from taking any further action.') }}</p>
                <div class="form">
                    <div class="row">
                        <div class="col-12 col-lg-6" id="lockTypeAll">
                            <label>{{ _i('Type') }}</label>
                            <select class="form-control" id="userLockType">
                                <option value="{{ route('agents.block') }}?=this" id="lockTypeThis">{{ _i('Only this user') }}</option>
                                <option value="{{ route('agents.role.lock-profile') }}?=all">{{ _i('This user and all its dependent users') }}</option>
                            </select>
                        </div>

                        <div class="col-12 col-lg-6">
                            <div class="form-group">
                                <label>{{ _i('Reason') }}</label>
                                <div id="userReasonLock" style="display: none">
                                    <select class="form-control" >
                                        <option value="Estafa">Estafa</option>
                                        <option value="Inactividad">Inactividad</option>
                                        <option value="Solicitado por el agente">Solicitado por el agente</option>
                                    </select>
                                </div>
                                <div id="userReasonUnlock" style="display: none">
                                    <select class="form-control">
                                        <option value="Hubo un error y no debio ser bloqueado">Hubo un error y no debio ser bloqueado</option>
                                        <option value="El usuario lo solicito">El usuario lo solicito</option>
                                        <option value="Sin comentarios">Sin comentarios</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-transparent" data-dismiss="modal"
                        data-lock="{{ _i('Cancel lock') }}"
                        data-unlock="{{ _i('Cancel unlock') }}">

                </button>
                <button type="button" class="btn btn-theme lockUser"
                        data-lock="{{ _i('Ready! Lock') }}"
                        data-unlock="{{ _i('Ready! Unlock') }}">
                </button>
            </div>
        </div>
    </div>
</div>
