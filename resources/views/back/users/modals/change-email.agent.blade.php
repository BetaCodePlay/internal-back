<div class="modal fade" id="chage-email-agent-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ _i('Send email') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('users.resend-activate-email') }}" method="post" id="resend-active-form">
                <div class="modal-body">
                    <h6>
                        {{_i('Send email for activation of account')  }}
                    </h6>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="username" id="username" value="">
                    <input type="hidden" name="email" id="email" value="">
                    <button type="button" class="btn u-btn-primary u-btn-3d" id="send-email" data-loading-text="{{ _i('Please wait...') }}">
                        {{_i('Send')  }}
                    </button>
                    <button type="button" class="btn u-btn-3d u-btn-bluegray u-btn-3d" data-dismiss="modal">
                        {{ _i('Close') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>