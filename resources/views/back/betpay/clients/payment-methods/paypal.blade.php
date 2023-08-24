    <div class="col-md-4 d-none paypal">
        <div class="form-group">
        <label for="client_id_paypal">{{ _i('Client ID') }}</label>
            <input type="text" name="client_id_paypal" class="form-control" autocomplete="off">
        </div>
    </div>
    <div class="col-md-4 d-none paypal">
        <div class="form-group">
        <label for="client_secret_paypal">{{ _i('Secret Key') }}</label>
            <input type="text" name="client_secret_paypal" class="form-control" autocomplete="off">
        </div>
    </div>
    <div class="col-md-6 d-none paypal">
        <div class="text-left">
            <p>
                {{ _i('Attention! Please note that fees will apply to paypal transactions.') }}
            </p>
        </div> 
    </div>