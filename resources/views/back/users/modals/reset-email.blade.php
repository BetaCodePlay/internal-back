<div class="modal fade" id="reset-email-modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ _i('Update email') }}</h5>
            </div>
            <form action="{{ route('users.reset-email') }}" method="post" id="reset-email-form">
                <div class="modal-body">
                    <div class="col-12">
                        <div class="form-group">
                            <label for="email">{{ _i('Email') }}</label>
                            <div class="input-group">
                                <input type="text" class="form-control" name="email">
                                <div class="input-group-append">
                                    <button class="btn u-input-btn--v1 g-width-40 u-btn-primary g-rounded-right-4 u-btn-3d refresh-password" type="button">
                                        <i class="hs-admin-reload g-absolute-centered g-font-size-16 g-color-white"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                <div class="modal-footer">
                    <input type="hidden" name="user" class="user" value="{{ isset($user) ? $user->id : '' }}">
                    <button type="button" class="btn u-btn-primary u-btn-3d" id="reset-email" data-loading-text="{{ _i('Please wait...') }}">
                        {{ _i('Update') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
