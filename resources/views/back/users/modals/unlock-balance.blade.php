<div class="modal fade" id="unlock-balance-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ _i('Unlock balance') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('users.unlock-balance') }}" method="post" id="unlock-balance-form">
                <div class="modal-body">
                    <h6>
                        {{_i('Are you sure you want to unlock the balance?') }}
                    </h6>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="unlock_wallet" id="unlock_wallet" value="{{ isset($wallet) ? $wallet->id : '' }}">
                    <input type="hidden" name="user_id" class="user_id" value="{{ isset($user) ? $user->id : '' }}">
                    <button type="button" class="btn u-btn-primary u-btn-3d" id="unclock" data-loading-text="{{ _i('Please wait...') }}">
                        {{_i('Unlock')  }}
                    </button>
                    <button type="button" class="btn u-btn-3d u-btn-bluegray u-btn-3d" data-dismiss="modal">
                        {{ _i('Close') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
