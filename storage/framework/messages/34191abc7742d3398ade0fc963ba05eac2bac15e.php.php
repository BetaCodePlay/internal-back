{
    "name": "dotworkers/wallet",
    "description": "Package to manage Wallet requests",
    "license": "Proprietary",
    "keywords": ["package", "manage", "wallet", "requests"],
    "type": "library",
    "authors": [
        {
            "name": "Eborio Linárez",
            "homepage": "https://todoprogramacion.com.ve",
            "email": "eborio@dotworkers.com"
        },
        {
            "name": "Gabriel Santiago",
            "email": "gabriel.santiago@dotworkers.com"
        }
    ],
    "require": {
        "ixudra/curl": "6.*"
    },
    "autoload": {
        "psr-4": {
            "Dotworkers\\Wallet\\": "src/Wallet/"
        }
    },
    "minimum-stability": "stable",
    "extra": {
        "laravel": {
            "providers": [
                "Dotworkers\\Wallet\\ServiceProvider"
            ]
        }
    }
}
