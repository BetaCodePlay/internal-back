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
            <div class="footer-top-right">
                <a href="#">{{ _i('Help') }}</a>
            </div>
        </div>
    </div>
</footer>
