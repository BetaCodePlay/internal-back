{
    "name": "dotworkers/bonus",
    "description": "Package to manage Whitelabels bonus",
    "license": "Proprietary",
    "keywords": ["package", "manage", "whitelabels", "bonus"],
    "type": "library",
    "authors": [
        {
            "name": "Damelys Espinoza"
        }
    ],
    "repositories": [
        {
            "type": "composer",
            "url": "https://libreria.bestcasinos.lat"
        }
    ],
    "require": {
        "dotworkers/configurations": "7.*",
        "dotworkers/sessions": "2.*",
        "dotworkers/wallet": "2.*",
        "jenssegers/agent": "^2.6"
    },
    "autoload": {
        "psr-4": {
            "Dotworkers\\Bonus\\": "src/Bonus/"
        }
    },
    "minimum-stability": "stable",
    "extra": {
        "laravel": {
            "providers": [
                "Dotworkers\\Bonus\\ServiceProvider"
            ]
        }
    }
}
