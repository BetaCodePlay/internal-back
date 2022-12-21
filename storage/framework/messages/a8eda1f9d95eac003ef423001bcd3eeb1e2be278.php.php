<div class="row g-mb-15">
    <div class="offset-md-4 offset-lg-6 offset-xl-6 col-xs-12 col-sm-12 col-md-4 col-lg-3 col-xl-3">
        <div class="form-group">
            <select name="transaction_type" id="transaction_type" class="form-control">
                <option value=""><?php echo e(_i('Transaction type...')); ?></option>
                <option value="<?php echo e(\Dotworkers\Configurations\Enums\TransactionTypes::$credit); ?>"><?php echo e(_i('Credit')); ?></option>
                <option value="<?php echo e(\Dotworkers\Configurations\Enums\TransactionTypes::$debit); ?>"><?php echo e(_i('Debit')); ?></option>
            </select>
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-4 col-lg-3 col-xl-3">
        <div class="input-group">
            <input type="text" id="date_range" class="form-control" autocomplete="off" placeholder="<?php echo e(_i('Date range')); ?>">
            <div class="input-group-append">
                <button class="btn g-bg-primary" type="button" id="update"
                        data-loading-text="<i class='fa fa-spin fa-refresh g-color-white'></i>">
                    <i class="hs-admin-reload g-color-white"></i>
                </button>
            </div>
        </div>
    </div>
</div>
