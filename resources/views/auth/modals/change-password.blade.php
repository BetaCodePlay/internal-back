<div class="modal-reset-password" data-success="{{ _i('Password changed successfully, wait a moment, you will be redirected shortly...') }}">
    <div class="modal-reset-password-ex">
        <div class="reset-password-bg"></div>
        <div class="reset-password-content">
            <div class="reset-password-content-ex">
                <div class="reset-password-close"><i class="fa fa-times"></i></div>
                <div class="reset-password-title">{{ _i('Reset password') }}</div>
                <div class="reset-password-subtitle">{{ _i('Enter the new password, remember that it has to be 8 characters and (,) or (.) as special.') }}</div>
                <div class="reset-password-body">
                    <label>{{ _i('Password')}}</label>
                    <div class="wrap-input-login validate-input" data-validate="{{ _i('Enter password')}}">
						<span class="btn-show-pass">
							<i class="fa fa-eye-slash"></i>
						</span>
                        <input class="input-login" type="password" name="password" id="reset-password" autocomplete="off" placeholder="{{ _i('At least 8 characters') }}" required>
                    </div>
                    <button type="button" class="btn-send" data-route="{{ route('auth.change-password') }}" data-loading-text="<i class='fa fa-spin fa-spinner'></i> {{ _i('Please wait...') }}">
                        {{ _i('Reset') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
