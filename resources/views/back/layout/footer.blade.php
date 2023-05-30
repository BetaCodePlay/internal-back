<footer id="footer"
        class="u-footer--bottom-sticky g-bg-white g-color-gray-dark-v6 g-brd-top g-brd-gray-light-v7 g-pa-20">
    <div class="row">
        <div class="offset-md-8 col-md-4 text-right">
            <div class="col-auto d-flex g-py-12 ml-auto">
                <div class="col-auto d-flex g-pt-5 g-pt-0--sm g-pl-5 g-pr-5">
                    <div class="g-pos-rel">
                        @if(!empty($whitelabel_currencies) && count($whitelabel_currencies)>1)
                            <a id="currency-menu-invoker" class="d-block" href="#!" aria-controls="currency-menu" aria-haspopup="true" aria-expanded="false" data-dropdown-event="click" data-dropdown-target="#currency-menu" data-dropdown-type="css-animation" data-dropdown-duration="300"
                               data-dropdown-animation-in="fadeIn" data-dropdown-animation-out="fadeOut">
                            <span class="g-pos-rel">
                                <span class="g-hidden-sm-down"><i class="fa fa-database"></i></span> {{ session('currency') == 'VEF' ? $free_currency->currency_name : session('currency') }}
                                <i class="hs-admin-angle-down g-pos-rel g-top-2 g-ml-10"></i>
                            </span>
                            </a>
                            <ul id="currency-menu" class="currency-menu-pro g-pos-abs g-left-0 g-nowrap g-font-size-14 g-py-20 g-mt-10 rounded" aria-labelledby="currency-menu-invoker">
                                @foreach ($whitelabel_currencies as $currency)
                                    <li class="mb-0">
                                        <a class="{{ $currency->iso == session('currency') ? 'active' : '' }} media g-color-primary--hover g-py-5 g-px-20" href="{{ route('core.change-currency', [$currency->iso]) }}">
                                        <span class="media-body align-self-center">
                                            {{ $currency->iso == 'VEF' ? $free_currency->currency_name : $currency->iso . " ({$currency->name})" }}
                                        </span>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <a id="currency-menu-invoker" class="d-block" href="#!" aria-controls="currency-menu" aria-haspopup="true" aria-expanded="false" data-dropdown-event="click" data-dropdown-target="#currency-menu" data-dropdown-type="css-animation" data-dropdown-duration="300"
                               data-dropdown-animation-in="fadeIn" data-dropdown-animation-out="fadeOut">
                                <span class="g-pos-rel">
                                    <span class="g-hidden-sm-down"><i class="fa fa-database"></i></span> {{ session('currency') == 'VEF' ? $free_currency->currency_name : session('currency') }}
                                    <i class="hs-admin-angle-down g-pos-rel g-top-2 g-ml-10"></i>
                                </span>
                            </a>
                        @endif

                        <span class="balanceAuth_{{\Illuminate\Support\Facades\Auth::id()}}"></span>
                    </div>
                </div>
            </div>
            {{--<div class="col-auto d-flex g-py-12 ml-auto">
                <div class="col-auto d-flex g-pt-5 g-pt-0--sm g-pl-5 g-pr-5">
                    @if(count($languages) > 1)
                        <div class="g-pos-rel">
                            <a id="languages-menu-invoker" class="d-block" href="#!" aria-controls="languages-menu" aria-haspopup="true" aria-expanded="false" data-dropdown-event="click" data-dropdown-target="#languages-menu" data-dropdown-type="css-animation" data-dropdown-duration="300"
                               data-dropdown-animation-in="fadeIn" data-dropdown-animation-out="fadeOut">
                            <span class="g-pos-rel">
                                <span class="g-hidden-sm-down"  {{ $selected_language['iso'] }}><i class="fa fa-globe"></i></span>
                                {{ $selected_language['name'] }}
                                <i class="hs-admin-angle-down g-pos-rel g-top-2 g-ml-10"></i>
                            </span>
                            </a>
                            <ul id="languages-menu" class="languages-menu-pro g-pos-abs g-left-0 g-nowrap g-font-size-14 g-py-20 g-mt-10 rounded" aria-labelledby="currency-menu-invoker">
                                @foreach ($languages as $language)
                                    <li class="mb-0">
                                        <a href="{{ route('core.change-language', [$language['iso']]) }}" class="change-language" data-locale="{{ $language['iso'] }}">
                                            <img class="lang-flag" src="{{ $language['flag'] }}" alt="{{ $language['name'] }}">
                                            {{ $language['name'] }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                </div>
            </div>--}}
            @if(count($languages) > 1)
                <div class="dropdown">
                    <button class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span {{ $selected_language['iso'] }}><i class="fa fa-globe"></i> {{ $selected_language['name'] }}</span>
                    </button>

                    <div class="dropdown-menu">
                        @foreach ($languages as $language)
                            <a class="dropdown-item" href="{{ route('core.change-language', [$language['iso']]) }}" data-locale="{{ $language['iso'] }}"><img class="lang-flag" src="{{ $language['flag'] }}" alt="{{ $language['name'] }}"> {{ $language['name'] }}</a>
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
