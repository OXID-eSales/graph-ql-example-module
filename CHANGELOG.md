# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [unreleased]

### Added
- custom `CategoryNotFound` Exception

### Changed
- `CategoryDao::getCategoryById` throws `CategoryNotFound` Exception and does not return `null` anymore
- Use OXID models and not own data models
- `DataObject` to `DataType`
- bumped dependency on `oxid-esales/graphql-base` to `^2.0.0`

## [1.1.0] 2019-12-10

### Added
- more infos in [README](README.md) file on how to use
- `timestamp` field for category
- made category language aware
- filtering capability

### Changed
- made unit tests real unit tests and run in travis
- moved `parent` and `children` to service
- renamed field `name` to `title`

## [1.0.2] 2019-11-08

### Added
- GraphQL description to queries and fields

### Changed
- move category factory to it's own class
- allow for serverside id generation

## [1.0.1] 2019-11-07

### Changed
- return type of `DataObject/Category::getChilds` not null
- license in readme file was wrongly stated as MIT
- version number in `metadata.php`

## [1.0.0] 2019-11-06

### Added
- Category queries, mutation, type and data access object
- `@Logged` and `@Right` annotations and updated tests

### Changed
- depends on `oxid-esales/graphql-base:^1.1`
- Namespace from `\OxidEsales\GraphQL` to `\OxidEsales\GraphQL\Example`
- PSR2 -> PSR12
