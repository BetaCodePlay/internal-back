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
                    <div class="form-group">
                        <label for="password">{{ _i('Password') }}</label>
                        <input type="password" name="password" id="password" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="password_confirmation">{{ _i('Confirm Password') }}</label>
                        <input type="password" name="password_confirmation" id="password_confirmation"
                               class="form-control">
                    </div>
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
