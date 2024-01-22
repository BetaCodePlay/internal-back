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
                <form autocomplete="destroy" class="form">
                    <div class="row">
                        <div class="col-12 col-lg-6">
                            <div class="form-group">
                                <label>{{ _i('Name') }}</label>
                                <input type="text" class="form-control" placeholder=""  id="createRolUsername">
                            </div>
                        </div>
                        <div class="col-12 col-lg-6">
                            <div class="form-group">
                                <label>{{ _i('Password') }}</label>
                                <div class="wrap-input">
                                    <input type="text" class="form-control" name="password" id="createRolPassword">
                                    <div class="wrap-element">
                                        <button class="btn btn-theme" type="button" id="createRoPasswordRefresh">
                                            <i class="fa-solid fa-arrows-rotate"></i>
                                        </button>
                                    </div>
                                </div>
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

                            <div class="col-12 col-lg-6">
                                <div class="form-group">
                                    <label>{{ _i('Dependence on') }}</label>
                                    <select class="form-control"  id="createRolDependence">
                                        <option value="">{{ _i('Select...') }}</option>
                                        @foreach ($dependencies as $dependece)
                                            <option value="{{ $dependece['user_id'] }}">
                                                {{ $dependece['username'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-12 col-lg-6 d-agent">
                                <div class="form-group">
                                    <label>{{ _i('Percentage') }}</label>
                                    <input type="text" name="percentage" class="form-control" placeholder="Rango disponible de 1 - 99"  id="createRolPercentage" data-max="99" data-min="1" value="1">
                                </div>
                            </div>
                        @endiF
                    </div>
                </form>
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
