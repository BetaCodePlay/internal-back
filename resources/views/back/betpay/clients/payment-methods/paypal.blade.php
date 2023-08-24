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
    <div class="col-md-12 d-none paypal">
        <div class="noty_bar noty_type__warning noty_theme__unify--v1--dark g-mb-25">
            <div class="noty_body">
                    <div class="g-mr-20">
                        <div class="noty_body__icon">
                            <i class="hs-admin-info"></i>
                        </div>
                    </div>
                    <div>
                        <p>
                            {{ _i('Attention! Please note that fees will apply to paypal transactions') }}
                        </p>
                    </div>
            </div>
        </div>
    </div>
    
                            