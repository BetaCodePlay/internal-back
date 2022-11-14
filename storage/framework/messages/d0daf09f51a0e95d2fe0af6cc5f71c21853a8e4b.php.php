{
    "name": "dotworkers/sessions",
    "description": "Package to manage Whitelabels sessions",
    "license": "Proprietary",
    "keywords": ["package", "manage", "whitelabels", "sessions"],
    "type": "library",
    "authors": [
        {
            "name": "Eborio Linárez",
            "homepage": "https://youcode.life",
            "email": "eborio@dotworkers.com"
        }
    ],
    "require": {

    },
    "autoload": {
        "psr-4": {
            "Dotworkers\\Sessions\\": "src/Sessions/"
        }
    },
    "minimum-stability": "stable",
    "extra": {
        "laravel": {
            "providers": [
                "Dotworkers\\Sessions\\ServiceProvider"
            ]
        }
    }
}
