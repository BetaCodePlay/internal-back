<div class="row g-mb-15">
    <div class="offset-md-8 offset-lg-9 offset-xl-9 col-xs-12 col-sm-12 col-md-4 col-lg-3 col-xl-3">
        <div class="input-group">
            <input type="text" id="daterange" class="form-control daterange" autocomplete="off" placeholder="{{ _i('Date range') }}">
            <input type="hidden" id="start_date" name="start_date">
            <input type="hidden" id="end_date" name="end_date">
            <div class="input-group-append">
                <button class="btn g-bg-primary" type="button" id="update"
                        data-loading-text="<i class='fa fa-spin fa-refresh g-color-white'></i>">
                    <i class="hs-admin-reload g-color-white"></i>
                </button>
            </div>
        </div>
    </div>
</div>
