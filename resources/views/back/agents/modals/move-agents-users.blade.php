<div class="modal fade" id="move-agents-users-modal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ _i('Move user') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('agents.move-agent-user') }}" id="move-agent-user-form" method="post">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="noty_bar noty_type__warning noty_theme__unify--v1--dark g-mb-25">
                                <div class="noty_body">
                                    <div class="g-mr-20">
                                        <div class="noty_body__icon">
                                            <i class="hs-admin-info"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <p>
                                            {{ _i('This tool allows you to move the user from one agent to another agent') }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="user" class="user">
                        <input type="hidden" name="type" class="type">
                        <div class="col-12 col-sm-6">
                            <div class="form-group">
                                <label for="agents">{{ _i('Agents') }}</label>
                                <select name="agent" id="agent" class="form-control" style="width: 100%">
                                    <option value="">{{ _i('Select...') }}</option>
                                    @foreach ($agents as $val)
                                        <option value="{{ $val['user_id'] }}">
                                            {{ $val['username'] }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn u-btn-3d u-btn-primary u-btn-3d" id="move-user-button" data-loading-text="<i class='fa fa-spin fa-spinner'></i> {{ _i('Please wait...') }}">
                        {{ _i('Move') }}
                    </button>
                    <button type="button" class="btn u-btn-3d u-btn-bluegray u-btn-3d" data-dismiss="modal">
                        {{ _i('Close') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
