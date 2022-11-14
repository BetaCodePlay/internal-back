{
    "name": "dotworkers/security",
    "description": "Package to manage Whitelabels security",
    "license": "Proprietary",
    "keywords": ["package", "manage", "whitelabels", "security"],
    "type": "library",
    "authors": [
        {
            "name": "Eborio Linárez",
            "homepage": "https://todoprogramacion.com.ve",
            "email": "eborio@dotworkers.com"
        },
        {
            "name": "Orlando Bravo",
            "email": "orlando.bravo@dotworkers.com"
        }
    ],
    "require": {

    },
    "autoload": {
        "psr-4": {
            "Dotworkers\\Security\\": "src/Security/"
        }
    },
    "minimum-stability": "stable",
    "extra": {
        "laravel": {
            "providers": [
                "Dotworkers\\Security\\ServiceProvider"
            ]
        }
    }
   
}
