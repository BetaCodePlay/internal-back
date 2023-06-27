<div class="modal fade" id="change-password">
    <div class="modal-dialog">
        <div class="modal-content bg-darkgray">
            <form action="{{ route('auth.change-password') }}" method="post" id="change-password-form">
                <div class="modal-header">
                    <h5 class="modal-title">{{ _i('Change password') }}</h5>
                    <button type="button" class="close btn-color-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    {{-- <div class="form-group">
                        <label for="oldPassword">{{ _i('Old Password') }}</label>
                        <input type="password" class="form-control" name="oldPassword" id="oldPassword">
                    </div> --}}
                    <div class="form-group">
                        <label for="newPassword">{{ _i('New Password') }}</label>
                        <input type="password" class="form-control" name="newPassword" id="newPassword">
                        <span class="info-change-password">Mínimo 8 caracteres, 1 letra y 1 número.</span>
                    </div>
                    <div class="form-group">
                        <label for="repeatNewPassword">{{ _i('Repeat New Password') }}</label>
                        <input type="password" class="form-control" name="repeatNewPassword" id="repeatNewPassword">
                        {{-- <span class="info-change-password">Mínimo 8 caracteres, 1 letra y 1 número.</span> --}}
                    </div>
                    {{-- <div class="col-12 col-sm-6">
                        <label for="password">{{ _i('Password') }}</label>
                        <div class="input-group">
                            <input type="text" class="form-control" name="password">
                            <div class="input-group-append">
                                <button
                                    class="btn u-input-btn--v1 g-width-40 u-btn-primary g-rounded-right-4 u-btn-3d refresh-password"
                                    type="button">
                                    <i class="hs-admin-reload g-absolute-centered g-font-size-16 g-color-white"></i>
                                </button>
                            </div>
                        </div>
                        <small
                            class="form-text text-muted">{{ _i('Minimum 8 characters, 1 letter and 1 number') }}</small>
                    </div> --}}
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="pUsername" id="pUsername">
                    <input type="hidden" name="oldPassword" id="oldPassword">
                    <button type="button" class="btn u-btn-primary u-btn-3d btn-color-gradient" id="update-button" data-loading-text="<i class='fa fa-spin fa-spinner'></i> {{ _i('Please wait...') }}">
                        {{ _i('Update') }}
                    </button>
                    {{-- <button type="button" class="btn u-btn-3d u-btn-bluegray u-btn-3d" data-dismiss="modal">
                        {{ _i('Close') }}
                    </button> --}}
                </div>
            </form>
        </div>
    </div>
</div>

<div class="col-12 col-sm-6">
    <label for="password">{{ _i('Password') }}</label>
    <div class="input-group">
        <input type="text" class="form-control" name="password">
        <div class="input-group-append">
            <button
                class="btn u-input-btn--v1 g-width-40 u-btn-primary g-rounded-right-4 u-btn-3d refresh-password"
                type="button">
                <i class="hs-admin-reload g-absolute-centered g-font-size-16 g-color-white"></i>
            </button>
        </div>
    </div>
    <small
        class="form-text text-muted">{{ _i('Minimum 8 characters, 1 letter and 1 number') }}</small>
</div>
