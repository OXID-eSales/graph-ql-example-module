# oxid-esales/graphql-example

[![Build Status](https://img.shields.io/travis/com/OXID-eSales/graphql-example-module.svg?style=for-the-badge&logo=travis)](https://travis-ci.com/OXID-eSales/graphql-example-module) [![PHP Version](https://img.shields.io/packagist/php-v/oxid-esales/graphql-example.svg?style=for-the-badge)](https://github.com/oxid-esales/graphql-example-module) [![Stable Version](https://img.shields.io/packagist/v/oxid-esales/graphql-example.svg?style=for-the-badge&label=latest)](https://packagist.org/packages/oxid-esales/graphql-example)

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

## Testing

### Linting, Syntax and static analysis

```bash
$ composer test
```

### Unit tests

- install this module into a running OXID eShop
- change the `test_config.yml`
  - add `oe/graphql-example` to the `partial_module_paths`
  - set `activate_all_modules` to `true`

```bash
$ ./vendor/bin/runtests
```

## License

GPLv3, see [LICENSE file](LICENSE).
