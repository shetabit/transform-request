# Changelog

All Notable changes to `request-transformer` will be documented in this file.

Updates should follow the [Keep a CHANGELOG](http://keepachangelog.com/) principles.

## Unreleased

### Added
- A test suite of 39 tests: unit tests for the transform object, the request macro, the facade and the helper, and
  feature tests that send real requests through a Laravel application built by Orchestra Testbench and run the
  `make:transformer` command.
- GitHub Actions workflows running the test suite (PHP 8.4 and 8.5 against Laravel 12 and 13, lowest and highest
  dependencies), the coding style check, the static analysis and the code coverage on every pull request and on every
  push to `master`.
- A `Dockerfile` (with pcov, for coverage) and a `Makefile` to run everything inside a container.
- A `phpcs.xml.dist` ruleset, a `phpstan.neon.dist` (level 7 with larastan, no baseline) and a `rector.php`.
- The `analyse`, `rector`, `test-coverage` and `ci` composer scripts.
- `Facade\Transform::SERVICE_NAME` and `Provider\ServiceProvider::REQUEST_MACRO`, so the container key and the macro
  name are no longer written out as strings in several places.

### Changed
- **Breaking:** PHP 8.4 is now the minimum required version (was PHP 7.2), and Laravel 12 and 13 are the supported
  versions (were Laravel 5.6+).
- **Breaking:** the `transform()` helper is called `transform_request()`. `transform()` is the name of a helper
  Laravel declares and calls itself, and `illuminate/support` is a dependency of this package, so composer loaded the
  framework's helpers first and this one was never defined at all — and had it ever won the race, every `transform()`
  inside the framework would have broken. The `$request->transform()` macro is unaffected and is still the documented
  way to reach the package.
- **Breaking:** every parameter and return value of `src/` declares a type.
- The container binding moved from `boot()` to `register()`, which is where Laravel expects bindings to be made.
- `shetabit/transformer` is required as `^2.0.1|^3.0`. **v2.0 of that package cannot be used**: its own
  `Transform::transform()` calls a `setTransformer()` that release does not define, so any use of it is a fatal error.
  v2.0.1 added the method. The upper half of the constraint takes transformer 3.x, which the suite is checked
  against.

### Removed
- The Travis CI configuration (`.travis.yml`), replaced by GitHub Actions.
- The StyleCI configuration (`.styleci.yml`), replaced by the PHP_CodeSniffer workflow.
- The `branch-alias` of `composer.json`.

## Date - 2019-03-09

### Fixed

### Added

### Deprecated

### Fixed

### Removed

### Security
