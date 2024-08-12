<footer class="footer">
    <div class="footer-ex">
        <div class="footer-top">
            <div class="footer-top-left">
                @if(!empty($logo))
                    @if(!is_null($logo->img_dark))
                        <img src="{{$logo->img_dark }}" alt="Logo" width="180" height="37" class="img-logo-footer">
                    @endif
                @endif
            </div>
            @if (\Dotworkers\Configurations\Configurations::getWhitelabel() == 2)
            @else
                <div class="footer-top-right">
                    <a href="#">{{ _i('Help') }}</a>
                </div>
            @endif
        </div>

        <div class="footer-bottom">
            <div class="footer-bottom-left">
                <div class="opt-footer-form-group">
                    <div class="form-group">
                        <select name="timezone" class="form-control change-timezone" data-route="{{ route('core.change-timezone') }}">
                            @foreach ($global_timezones as $global_timezone)
                                <option value="{{ $global_timezone['timezone'] }}" {{ $global_timezone['timezone'] == session()->get('timezone') ? 'selected' : '' }}>
                                    {{ $global_timezone['text'] }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            @if (\Dotworkers\Configurations\Configurations::getWhitelabel() == 2)
            @else
                <div class="footer-bottom-right">
                    <a href="#">{{ _i('Legal information') }}</a>
                    <a href="#">{{ _i('Privacy policies') }}</a>
                </div>
            @endif
        </div>

        @if(count($languages) > 1)
            <div class="footer-bottom">
                <div class="footer-bottom-left">
                    <div class="opt-footer-form-group">
                        <div class="form-group">
                            <div class="dropdown-footer g-pos-rel dropdown">
                                <button class="btn dropdown-toggle" type="button" id="dropdownLanguage" data-toggle="dropdown" aria-expanded="false">
                                    {{ session('currency') == 'VEF' ? $free_currency->currency_name : session('currency') }} <i class="fa-solid fa-angle-down"></i>
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="dropdownLanguage">
                                    @foreach ($languages as $language)
                                        <li>
                                            <a class="dropdown-item" href="{{ route('core.change-language', [$language['iso']]) }}">
                                                {{ $language['name'] }} <span class="mini-title">{{ $language['iso'] }}</span>
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</footer>
