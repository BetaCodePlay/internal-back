{
    "name": "dotworkers/laravel-gettext",
    "description": "Adds localization support to laravel applications in an easy way using Poedit and GNU gettext.",
    "homepage": "https://github.com/zerospam/laravel-gettext",
    "keywords": [
        "gettext",
        "localization",
        "poedit",
        "laravel-gettext",
        "laravel", "translation"
    ],
    "license": "MIT",
    "authors": [
        {
            "name": "Nicolás Daniel Palumbo",
            "email": "n@xinax.net"
        },
        {
            "name": "Antoine Aflalo",
            "email": "dev+laravel_gettext@zerospam.ca"
        }
    ],
    "support": {
        "issues": "https://github.com/zerospam/laravel-gettext/issues"
    },
    "require": {
        "php": "^7.1|^8.0",
        "laravel/framework": "^6.0 || ^7.0 || ^8.0 || ^9.0 || ^10.0",
        "laravel/helpers": "^1.1"
    },
    "require-dev": {
        "mockery/mockery": "dev-master",
        "phpunit/phpunit": "^8.0 || ^8.5",
        "squizlabs/php_codesniffer" : "1.5.*",
        "laravel/laravel": "^6.0 || ^7.0",
        "php-coveralls/php-coveralls": "^2.1"
    },
    "autoload": {
        "psr-0": {
            "Xinax\\LaravelGettext\\": "src/"
        },
        "files": [
            "src/Xinax/LaravelGettext/Support/helpers.php"
        ]
    },
    "minimum-stability": "stable",
    "extra": {
        "laravel": {
          "providers": [
            "Xinax\\LaravelGettext\\LaravelGettextServiceProvider"
          ]
       }
    }
}
