<footer id="footer"
        class="u-footer--bottom-sticky g-bg-white g-color-gray-dark-v6 g-brd-top g-brd-gray-light-v7 g-pa-20">
    <div class="row">
        <div class="offset-md-8 col-md-4 text-right">
            <small class="d-block g-font-size-default">
                {{ $whitelabel_info->copyright ? _i('Developed by Dotworkers. Operated by') : '' }} {{ $whitelabel_description }} © {{ _i('Copyright') }} - {{ date('Y') }}. {{ _i('All rights reserved') }}
            </small>
        </div>
    </div>
</footer>
