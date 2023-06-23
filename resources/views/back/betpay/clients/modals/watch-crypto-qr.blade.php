<div class="modal fade" id="watch-crypto-qr-modal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ _i('Cryptocurrency QR') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="text-center" id="qr-crypto"></div>
                <br>
                <div class="row">
                    <div class="col-md-12 text-center" id="payment">
                        <label><strong>{{ _i('Wallet') }}</strong></label> <div id="data-wallet"></div> <br>
                        <label><strong>{{ _i('Cryptocurrency') }}</strong></label><div id="data-cryptocurrency"></div>   
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn u-btn-3d u-btn-bluegray u-btn-3d" data-dismiss="modal">
                    {{ _i('Close') }}
                </button>
            </div>
        </div>
    </div>
</div>
