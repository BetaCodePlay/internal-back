<div class="col-md-4 d-none cryptocurrency">
        <div class="form-group">
            <label for="cryptocurrency_cripto"><?php echo e(_i('Cryptocurrency')); ?></label>
            <select name="cryptocurrency_cripto" class="form-control cryptocurrency_cripto">
                <option value=""><?php echo e(_i('Select...')); ?></option>
                <option value="USDT"><?php echo e(_i('USDT')); ?></option>
                <option value="BTC"><?php echo e(_i('BTC')); ?></option>
                <option value="USDC"><?php echo e(_i('USDC')); ?></option>
            </select>
        </div>
    </div>
    <div class="col-md-4 d-none cryptocurrency">
        <div class="form-group">
            <label for="wallet_cripto"><?php echo e(_i('Wallet')); ?></label>
                <input type="text" name="wallet_cripto" class="form-control" autocomplete="off">
        </div>
    </div>
    <div class="col-md-4 d-none cryptocurrency">
        <div class="form-group">
            <label for="qr_cripto"><?php echo e(_i('QR')); ?></label>
                <input type="file" name="qr_cripto" id="qr_cripto" class="form-control" autocomplete="off">
        </div>
    </div>
    <div class="col-md-4 d-none cryptocurrency">
        <div class="form-group">
            <label for="network_cripto"><?php echo e(_i('Network')); ?></label>
                <input type="text" name="network_cripto" id="network_cripto" class="form-control" autocomplete="off">
        </div>
    </div>
    
   