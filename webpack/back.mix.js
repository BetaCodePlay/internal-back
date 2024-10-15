const mix = require("laravel-mix");

mix.setPublicPath("public/back/");

/*
|--------------------------------------------------------------------------
| SASS
|--------------------------------------------------------------------------
*/
/*mix.sass(
    "resources/assets/back/scss/template.scss",
    "public/back/css/template.min.css"
).version();*/

/*
 |--------------------------------------------------------------------------
 | JS
 |--------------------------------------------------------------------------
 */

mix.autoload({
    jquery: ["$", "jQuery", "jquery", "window.jQuery"],
    "popper.js/dist/umd/popper.js": ["Popper"],
})
    .js(
        [
            "resources/js/app.js",
            "resources/assets/back/js/bootstrap.js",
            "resources/assets/back/js/configurations.js",
            "resources/assets/back/js/users.js",
            "resources/assets/back/js/audits.js",
            "resources/assets/back/js/sliders.js",
            "resources/assets/back/js/pages.js",
            "resources/assets/back/js/agents.js",
            "resources/assets/back/js/roles.js",
            "resources/assets/back/js/betpay.js",
            "resources/assets/back/js/reports.js",
            "resources/assets/back/js/section-images.js",
            "resources/assets/back/js/section-modals.js",
            "resources/assets/back/js/posts.js",
            "resources/assets/back/js/store.js",
            "resources/assets/back/js/iq-soft.js",
            "resources/assets/back/js/providers-limits.js",
            "resources/assets/back/js/notifications.js",
            "resources/assets/back/js/security.js",
            "resources/assets/back/js/core.js",
            "resources/assets/back/js/whitelabels.js",
            "resources/assets/back/js/bonus-system.js",
            "resources/assets/back/js/socket.js",
            "resources/assets/back/js/pusher-setup.js",
            "resources/assets/back/js/email-configurations.js",
            "resources/assets/back/js/lobby-games.js",
            "resources/assets/back/js/landing-pages.js",
            "resources/assets/back/js/section-games.js",
            "resources/assets/back/js/dashboard.js",
            "resources/assets/back/js/email-templates.js",
            "resources/assets/back/js/marketing-campaigns.js",
            "resources/assets/back/js/segments.js",
            "resources/assets/back/js/whitelabels-games.js",
            "resources/assets/back/js/financial-report.js",
            "resources/assets/back/js/dotsuite.js",
            "resources/assets/back/js/referrals.js",
            "resources/assets/back/js/invoices.js",
            "resources/assets/back/js/commons.js",
            "resources/assets/back/js/main.js",
            "resources/assets/back/js/chat.js",
            "resources/assets/back/js/global.js",
            "resources/assets/commons/plugins/toastr/js/toastr.min.js",
        ],
        "public/back/js/custom.min.js"
    )
    .extract()
    .version();
