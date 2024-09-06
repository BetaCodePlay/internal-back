<div class="modal fade modal-style" id="role-modify">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ _i('Modify role') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="loading-style"></div>
                <div id="readyRoleModify" class="d-none">
                    <p><span class="font-weight-bold">{{ _i('What role do you want to create?') }}</span> <span class="username-form"></span></p>
                    <p>{{ _i('You will be able to create master and support agents initially, then you can assign players if necessary.') }}</p>
                    <form autocomplete="destroy" class="form">
                        <div class="row">
                            <div class="col-12 col-lg-6">
                                <div class="form-group">
                                    <label>{{ _i('Dependence on') }}</label>
                                    <select class="form-control" id="modifyRolDependence">
                                        <option value="">{{ _i('Select...') }}</option>
                                        <option value="{{ auth()->user()->id }}">{{ auth()->user()->username }}</option>
                                        @foreach ($dependencies as $dependece)
                                            @if($authUser->id !== $dependece['user_id'])
                                                <option value="{{ $dependece['user_id'] }}">
                                                    {{ $dependece['username'] }}
                                                </option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-12 col-lg-6 d-agent">
                                <div class="form-group">
                                    <label>{{ _i('Percentage') }}</label>
                                    <div class="wrap-input">
                                        <input type="text" name="percentage" class="form-control" placeholder="Rango disponible de 1 - 99" id="modifyRolPercentage" data-max="99">
                                        <div class="wrap-element">
                                            <div class="wrap-element-text">%</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="modal-footer d-none">
                <button type="button" class="btn btn-transparent" data-dismiss="modal">
                    {{ _i('Cancel creation') }}
                </button>
                <button type="button" class="btn btn-theme modifyUser" data-route="{{ route('agents.role.update-rol') }}" data-loading-text="<i class='fa fa-spin fa-spinner'></i> {{ _i('Modifying role') }}...">
                    {{ _i('Ready! Modify role') }}
                </button>
            </div>
        </div>
    </div>
</div>
