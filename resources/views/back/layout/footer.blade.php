<footer id="footer"
        class="u-footer--bottom-sticky g-bg-white g-color-gray-dark-v6 g-brd-top g-brd-gray-light-v7 g-pa-20">
    <div class="row">
        <div class="offset-md-8 col-md-4 text-right opt-footer">
            @if(!empty($whitelabel_currencies) && count($whitelabel_currencies)>1)
                <div class="dropdown">
                    <button class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span><i class="fa fa-database"></i> {{ session('currency') == 'VEF' ? $free_currency->currency_name : session('currency') }}</span> <i class="fa fa-caret-up"></i>
                    </button>

                    <div class="dropdown-menu">
                        @foreach ($whitelabel_currencies as $currency)
                            <a class="dropdown-item" href="{{ route('core.change-currency', [$currency->iso]) }}"><i class="fa fa-database"></i> {{ $currency->iso == 'VEF' ? $free_currency->currency_name : $currency->iso . " ({$currency->name})" }}</a>
                        @endforeach
                    </div>
                </div>
            @endif

            @if(count($languages) > 1)
                <div class="dropdown">
                    <button class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span {{ $selected_language['iso'] }}><i class="fa fa-globe"></i> {{ $selected_language['name'] }}</span> <i class="fa fa-caret-up"></i>
                    </button>

                    <div class="dropdown-menu">
                        @foreach ($languages as $language)
                            <a class="dropdown-item" href="{{ route('core.change-language', [$language['iso']]) }}" class="change-language" data-locale="{{ $language['iso'] }}"><img class="lang-flag" src="{{ $language['flag'] }}" alt="{{ $language['name'] }}"> {{ $language['name'] }}</a>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
        <div class="offset-md-8 col-md-4 text-right">
            <small class="d-block g-font-size-default">
                {{ $whitelabel_info->copyright ? _i('Developed by Betsweet. Operated by') : '' }} {{ $whitelabel_description }} © {{ _i('Copyright') }} - {{ date('Y') }}. {{ _i('All rights reserved') }}
            </small>
        </div>
    </div>
</footer>
