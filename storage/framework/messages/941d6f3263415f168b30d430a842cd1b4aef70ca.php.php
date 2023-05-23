{
    "name": "dotworkers/audits",
    "description": "Package to manage Whitelabels audits",
    "license": "Proprietary",
    "keywords": ["package", "manage", "whitelabels", "audits"],
    "type": "library",
    "authors": [
        {
            "name": "Eborio Linárez",
            "email": "eborio.linarez@dotworkers.com"
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
            "Dotworkers\\Audits\\": "src/Audits/"
        }
    },
    "minimum-stability": "stable",
    "extra": {
        "laravel": {
            "providers": [
                "Dotworkers\\Audits\\ServiceProvider"
            ]
        }
    }
   
}
