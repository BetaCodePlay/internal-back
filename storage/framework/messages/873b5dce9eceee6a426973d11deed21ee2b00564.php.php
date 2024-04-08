{
    "name": "dotworkers/store",
    "description": "Package to manage Whitelabels store",
    "license": "Proprietary",
    "keywords": ["package", "manage", "whitelabels", "store"],
    "type": "library",
    "authors": [
        {
            "name": "Damelys Espinoza"
        },
        {
            "name": "Eborio Linárez",
            "email": "eborio.linarez@dotworkers.com"
        }
    ],
    "repositories": [
        {
            "type": "composer",
            "url": "https://libreria.bestcasinos.lat"
        }
    ],
    "require": {
        "jenssegers/agent": "^2.6",
        "dotworkers/configurations": "7.*"
    },
    "autoload": {
        "psr-4": {
            "Dotworkers\\Store\\": "src/Store/"
        }
    },
    "minimum-stability": "stable",
    "extra": {
        "laravel": {
            "providers": [
                "Dotworkers\\Store\\ServiceProvider"
            ]
        }
    }
}
