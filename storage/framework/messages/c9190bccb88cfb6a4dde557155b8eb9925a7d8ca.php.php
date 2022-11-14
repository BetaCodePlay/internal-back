{
    "name": "dotworkers/configurations",
    "description": "Package to manage Whitelabels configurations",
    "license": "Proprietary",
    "keywords": ["package", "manage", "whitelabels", "configurations"],
    "type": "library",
    "authors": [
        {
            "name": "Damelys Espinoza",
            "email": "damelys.espinoza@dotworkers.com"
        },
        {
            "name": "Eborio Linárez",
            "homepage": "https://youcode.life",
            "email": "eborio@dotworkers.com"
        },
        {
            "name": "Miguel Sira",
            "email": "miguel.sira@dotworkers.com"
        },
        {
            "name": "Orlando Bravo",
            "email": "orlando.bravo@dotworkers.com"
        },
        {
            "name": "Yeltsin Linares",
            "email": "yeltsin.linares@dotworkers.com"
        }
    ],
    "repositories": [
        {
            "type": "composer",
            "url": "https://packages.dotworkers.net"
        }
    ],
    "require": {
        "dotworkers/laravel-gettext": "7.x",
        "dotworkers/wallet": "2.*",
        "lcobucci/jwt": "4.*"
    },
    "autoload": {
        "psr-4": {
            "Dotworkers\\Configurations\\": "src/Configurations/"
        },
        "files": [
            "src/Configurations/helpers.php"
        ]
    },
    "minimum-stability": "stable",
    "extra": {
        "laravel": {
            "providers": [
                "Dotworkers\\Configurations\\ServiceProvider"
            ]
        }
    }
}
