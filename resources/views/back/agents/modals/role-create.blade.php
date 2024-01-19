<div class="modal fade modal-style" id="role-create">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ _i('Create role') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p><span class="font-weight-bold">{{ _i('What role do you want to create?') }}</span></p>
                <p>{{ _i('You will be able to create master and support agents initially, then you can assign players if necessary.') }}</p>
                <div class="form">
                    <div class="row">
                        <div class="col-12 col-lg-6">
                            <div class="form-group">
                                <label>{{ _i('Name') }}</label>
                                <input type="text" class="form-control" placeholder=""  id="createRolUsername">
                            </div>
                        </div>
                        @if ($agent->master)
                            <div class="col-12 col-lg-6">
                                <div class="form-group">
                                    <label>{{ _i('Role type') }}</label>
                                    <select class="form-control"  id="createRolType">
                                        <option value="true">{{ _i('Master') }}</option>
                                        <option value="false">{{ _i('Support') }}</option>
                                        <option value="">{{ _i('Players') }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-12 col-lg-6 d-agent">
                                <div class="form-group">
                                    <label>{{ _i('Percentage') }}</label>
                                    <input type="text" name="percentage" class="form-control" placeholder="Rango disponible de 1 - 99"  id="createRolPercentage">
                                </div>
                            </div>
                            <div class="col-12 col-lg-6 d-agent">
                                <div class="form-group">
                                    <label>{{ _i('Dependence on') }}</label>
                                    <select class="form-control"  id="createRolDependence">
                                        <option value="">{{ _i('Select...') }}</option>
                                        @foreach ($dependencies as $dependece)
                                            <option value="{{ $dependece['id'] }}">
                                                {{ $dependece['username'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        @endiF
                        <div class="col-12 col-lg-6">
                            <div class="form-group">
                                <label>{{ _i('Password') }}</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" name="password" id="createRolPassword">
                                    <div class="input-group-append">
                                        <button class="btn u-input-btn--v1 g-width-40 u-btn-primary g-rounded-right-4 u-btn-3d refresh-password" type="button">
                                            <i class="hs-admin-reload g-absolute-centered g-font-size-16 g-color-white"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-transparent" data-dismiss="modal">
                    {{ _i('Cancel creation') }}
                </button>
                <button type="button" class="btn btn-theme createUser" data-route-agent="{{ route('agents.role.store-rol') }}" data-route-player="{{ route('agents.role.store-user') }}">
                    {{ _i('Ready! Create role') }}
                </button>
            </div>
        </div>
    </div>
</div>
