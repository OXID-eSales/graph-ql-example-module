# oxid-esales/graphql-example

[![Build Status](https://img.shields.io/travis/com/OXID-eSales/graphql-example-module/master.svg?style=for-the-badge&logo=travis)](https://travis-ci.com/OXID-eSales/graphql-example-module) [![PHP Version](https://img.shields.io/packagist/php-v/oxid-esales/graphql-example.svg?style=for-the-badge)](https://github.com/oxid-esales/graphql-example-module) [![Stable Version](https://img.shields.io/packagist/v/oxid-esales/graphql-example.svg?style=for-the-badge&label=latest)](https://packagist.org/packages/oxid-esales/graphql-example)

This module provides a basic example on how to extend the [oxid-esales/graphql-base](https://github.com/OXID-eSales/graphql-base-module) module.

## Usage

This assumes you have the OXID eShop up and running and installed and activated the `oxid-esales/graphql-base` module.

**DO NOT USE IN PRODUCTION, THIS MODULE IS FOR DEMO PURPOSES ONLY**

### Install

```bash
$ composer require oxid-esales/graphql-example
```

After requiring the module, you need to head over to the OXID eShop admin and
activate the GraphQL Example module.

### How to use

1. Install [oxid-esales/graphql-base](https://github.com/OXID-eSales/graphql-base-module) module
2. Get token (see base module documentation)
3. Execute example query to get all categories

Request query

```graphql
query {
  categories {
    id
    name
  }
}
```

Response example
```json
{
    "data": {
        "categories": [
            {
                "id": "30e44ab83fdee7564.23264141",
                "name": "Bekleidung"
            },
            {
                "id": "943173edecf6d6870a0f357b8ac84d32",
                "name": "Wakeboarding"
            },
            {
                "id": "943a9ba3050e78b443c16e043ae60ef3",
                "name": "Kiteboarding"
            },
            {
                "id": "fadcb6dd70b9f6248efa425bd159684e",
                "name": "Angebote"
            },
            {
                "id": "oia9ff5c96f1f29d527b61202ece0829",
                "name": "Downloads"
            }
        ]
    }
}
```

## Testing

### Linting, syntax, static analysis and unit tests

```bash
$ composer test
```

### Integration tests

- install this module into a running OXID eShop
- change the `test_config.yml`
  - add `oe/graphql-example` to the `partial_module_paths`
  - set `activate_all_modules` to `true`

```bash
$ ./vendor/bin/runtests
```

## License

GPLv3, see [LICENSE file](LICENSE).
