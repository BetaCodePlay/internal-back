<div class="col-md-4 d-none cryptocurrency">
        <div class="form-group">
            <label for="cryptocurrency">{{ _i('Cryptocurrency') }}</label>
            <select name="cryptocurrency" class="form-control cryptocurrency">
                <option value="">{{ _i('Select...') }}</option>
                <option value="USDT">{{ _i('USDT') }}</option>
            </select>
        </div>
    </div>
    <div class="col-md-4 d-none cryptocurrency">
        <div class="form-group">
            <label for="wallet">{{ _i('Wallet') }}</label>
                <input type="text" name="wallet" class="form-control" autocomplete="off">
        </div>
    </div>
    <div class="col-md-4 d-none cryptocurrency">
        <div class="form-group">
            <label for="network">{{ _i('Network') }}</label>
                <input type="text" name="network" id="network" class="form-control" autocomplete="off">
        </div>
    </div>
    <div class="col-md-4 d-none cryptocurrency">
        <div class="form-group">
            <label for="image">{{ _i('QR') }}</label>
                <input type="file" name="image" id="image" class="form-control" autocomplete="off">
        </div>
    </div>
   