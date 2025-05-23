{
    "name": "dotworkers/alerts",
    "description": "Package to manage Whitelabels alerts",
    "license": "Proprietary",
    "keywords": ["package", "manage", "whitelabels", "alerts"],
    "type": "library",
    "authors": [
        {
            "name": "Derluin Gonzalez",
            "email": "derluinjose@gmail.com"
        },
        {
            "name": "Orlando Bravo",
            "email": "orlando.bravo@dotworkers.com"
        }
    ],
    "repositories": [
        {
            "type": "composer",
            "url": "https://packages.24live.bet"
        }
    ],
    "require": {
        "dotworkers/configurations": "7.*"
    },
    "autoload": {
        "psr-4": {
            "Dotworkers\\Alerts\\": "src/Alerts/"
        }
    },
    "minimum-stability": "stable",
    "extra": {
        "laravel": {
            "providers": [
                "Dotworkers\\Alerts\\ServiceProvider"
            ]
        }
    }
   
}
