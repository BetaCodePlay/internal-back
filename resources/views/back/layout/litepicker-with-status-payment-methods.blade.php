<div class="row g-mb-15">
    <div class="offset-md-4 offset-lg-4 offset-xl-2 col-xs-3 col-sm-3 col-md-2 col-lg-3 col-xl-3">
        <div class="form-group">
            <select name="status" id="status" class="form-control">
                <option value="">{{ _i('Status...') }}</option>
                <option value="{{ \Dotworkers\Configurations\Enums\TransactionStatus::$pending }}">{{ _i('Pending') }}</option>
                <option value="{{ \Dotworkers\Configurations\Enums\TransactionStatus::$approved }}">{{ _i('Approved') }}</option>
                <option value="{{ \Dotworkers\Configurations\Enums\TransactionStatus::$rejected}}">{{ _i('Rejected') }}</option>
            </select>
        </div>
    </div>
    <div class="offset-md-2 offset-lg-2 offset-xl-2 col-xs-3 col-sm-3 col-md-2 col-lg-3 col-xl-3">
        <div class="form-group">
            <select name="payment" id="payment" class="form-control">
                <option value="">{{ _i('Payment methods...') }}</option>
                <option value="*">{{ _i('All') }}</option>
                @foreach ($payment_methods as $payment)
                    <option value="{{ $payment->id }}">
                        {{ $payment->name }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-4 col-lg-3 col-xl-3">
        <div class="input-group">
            <input type="text" id="date_range" class="form-control" autocomplete="off" placeholder="{{ _i('Date range') }}">
            <div class="input-group-append">
                <button class="btn g-bg-primary" type="button" id="update"
                        data-loading-text="<i class='fa fa-spin fa-refresh g-color-white'></i>">
                    <i class="hs-admin-reload g-color-white"></i>
                </button>
            </div>
        </div>
    </div>
</div>
