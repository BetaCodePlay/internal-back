<div class="g-mb-15">
    <div class="media-body d-flex justify-content-end">
        <div
            class="daterange u-datepicker-right u-datepicker--v3 g-pos-rel g-cursor-pointer g-brd-around g-brd-gray-light-v7 g-rounded-4">
            <input type="text" id="daterange" class="js-range-datepicker g-pr-80 g-pl-15 g-py-9"
                   placeholder="<?php echo e(_i('Date range')); ?>" autocomplete="off">
            <input type="hidden" id="start_date" name="start_date" value="2020-02-01">
            <input type="hidden" id="end_date" name="end_date" value="2020-02-01">
            <div
                class="d-flex align-items-center g-absolute-centered--y g-right-0 g-color-gray-light-v6 g-color-lightblue-v9--sibling-opened g-mr-15">
                <i class="hs-admin-calendar g-font-size-18 g-mr-10"></i>
                <i class="hs-admin-angle-down"></i>
            </div>
        </div>
        <button class="btn g-bg-primary" type="button" id="update"
                data-loading-text="<i class='fa fa-spin fa-refresh g-color-white'></i>">
            <i class="hs-admin-reload g-color-white"></i>
        </button>
    </div>
</div>
