<div class="col-md-4 d-none cryptocurrency">
        <div class="form-group">
            <label for="cryptocurrency_cripto">{{ _i('Cryptocurrency') }}</label>
            <select name="cryptocurrency_cripto" class="form-control cryptocurrency_cripto">
                <option value="">{{ _i('Select...') }}</option>
                <option value="USDT">{{ _i('USDT') }}</option>
                <option value="ETH">{{ _i('ETH') }}</option>
                <option value="BTC">{{ _i('BTC') }}</option>
                <option value="LTC">{{ _i('LTC') }}</option>
                <option value="USDC">{{ _i('USDC') }}</option>
            </select>
        </div>
    </div>
    <div class="col-md-4 d-none cryptocurrency">
        <div class="form-group">
            <label for="wallet_cripto">{{ _i('Wallet') }}</label>
                <input type="text" name="wallet_cripto" class="form-control" autocomplete="off">
        </div>
    </div>
    <div class="col-md-4 d-none cryptocurrency">
        <div class="form-group">
            <label for="qr_cripto">{{ _i('QR') }}</label>
                <input type="file" name="qr_cripto" id="qr_cripto" class="form-control" autocomplete="off">
        </div>
    </div>
    <div class="col-md-4 d-none cryptocurrency">
        <div class="form-group">
            <label for="network_cripto">{{ _i('Network') }}</label>
                <input type="text" name="network_cripto" id="network_cripto" class="form-control" autocomplete="off">
        </div>
    </div>
    
   