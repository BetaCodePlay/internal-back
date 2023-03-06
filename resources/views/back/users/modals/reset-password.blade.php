<div class="modal fade" id="reset-password-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ _i('Reset password') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('users.reset-password') }}" method="post" id="reset-password-form">
                <div class="modal-body">
                    <div class="col-12">
                        <div class="form-group">
                            <label for="password">{{ _i('Password') }}</label>
                            <div class="input-group">
                                <input type="text" class="form-control" name="password">
                                <div class="input-group-append">
                                    <button class="btn u-input-btn--v1 g-width-40 u-btn-primary g-rounded-right-4 u-btn-3d refresh-password" type="button">
                                        <i class="hs-admin-reload g-absolute-centered g-font-size-16 g-color-white"></i>
                                    </button>
                                </div>
                            </div>
                            <small class="form-text text-muted">{{ _i('Minimum 8 characters, 1 letter and 1 number') }}</small>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-group">
                            <label for="password">{{ _i('Confirm Password') }}</label>
                            <div class="input-group">
                                <input type="text" name="password_confirmation" id="password_confirmation"
                                       class="form-control" autocomplete="off">
                            </div>
                        </div>
                    </div>
{{--                    --}}
{{--                    <div class="form-group">--}}
{{--                        <label for="password">{{ _i('Password') }}</label>--}}
{{--                        <input type="password" name="password" id="password" class="form-control">--}}
{{--                    </div>--}}
{{--                    <div class="form-group">--}}
{{--                        <label for="password_confirmation">{{ _i('Confirm Password') }}</label>--}}
{{--                        <input type="password" name="password_confirmation" id="password_confirmation"--}}
{{--                               class="form-control">--}}
{{--                    </div>--}}
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="user" class="user" value="{{ isset($user) ? $user->id : '' }}">
                    <button type="button" class="btn u-btn-primary u-btn-3d" id="reset-password" data-loading-text="{{ _i('Please wait...') }}">
                        {{ _i('Reset') }}
                    </button>
                    <button type="button" class="btn u-btn-3d u-btn-bluegray u-btn-3d" data-dismiss="modal">
                        {{ _i('Close') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
