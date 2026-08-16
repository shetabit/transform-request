
<p align="center">
    <img src="resources/images/diagram.png?raw=true">
</p>



# Transform laravel requests

[![Software License][ico-license]](LICENSE.md)
[![Latest Version on Packagist][ico-version]][link-packagist]
[![Total Downloads on Packagist][ico-download]][link-packagist]
[![Tests][ico-tests]][link-tests]
[![Code Style][ico-code-style]][link-code-style]
[![Static Analysis][ico-static-analysis]][link-static-analysis]
[![Code Coverage][ico-coverage]][link-coverage]

you can **normalize** or **change request data structure** with transformers.

This package supports `PHP 8.4+` and `Laravel 12` and `13`.

> lets **normalize** our data in `transformers` and let `controllers` to be much more **cleaner** and **smaller**.

## List of contents

- [Install](#install)
- [How to use](#how-to-use)
  - [Create a new data transformer](#create-a-new-data-transformer)
  - [Transform requests](#transform-requests)
- [Change log](#change-log)
- [Contributing](#contributing)
- [Security](#security)
- [Credits](#credits)
- [License](#license)

## Install

Via Composer

```bash
$ composer require shetabit/transform-request
```

## How to use

#### Create a new data transformer

> we use transformers to transform request data.

you can run the below command in your console in order to create a new data transformer named `TestTransformer`.

```bash
$ composer php artisan make:transformer TestTransformer
```

all transformers will be created in `App\Http\Transformers` path.

#### Transformer example:

in all transformers, the `transform` method will transform your data into your ideal one.
for example we can write the below code in it:

```php
namespace App\Http\Transformers;

use Shetabit\Transformer\Contracts\TransformerInterface;

class TestTransformer implements TransformerInterface
{
    /**
     * transform given data
     *
     * @param array $data
     * @return array
     */
    public function transform(array $data) : array {
        /*
            input data :		
            [
                'n' => 'mahdi',
                'f' => 'khanzadi'
            ]

            transformed data:
            [
                'name' => 'mahdi',
                'family' => 'khanzadi',
                'username' => 'mahdikhanzadi'
            ]
        */

        return [
            'name' => $data['n'] ?? null,
            'family' => $data['f'] ?? null,
            'username' => ($data['n'] ?? null).($data['f'] ?? null)
        ];
    }
}
```

#### Transform requests

we can use a transformer to transform requests like the below:

```php
namespace App\Http\Controllers;

use App\Http\Transformers\TestTransformer;
use Illuminate\Http\Request;

class TestController extends Controller
{
    public function __invoke(Request $request) {
        $data = $request->transform()->get(new TestTransformer());
        
        print_r($data)
    }
}
```

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) and [CONDUCT](CONDUCT.md) for details.

## Security

If you discover any security related issues, please email khanzadimahdi@gmail.com instead of using the issue tracker.

## Credits

- [Mahdi khanzadi][link-author]
- [All Contributors][link-contributors]

## Testing

Every pull request and every push to `master` is checked by [GitHub Actions][link-actions]: the test suite runs on
PHP 8.4 and 8.5 against Laravel 12 and 13 (both the lowest and the highest supported dependencies), the coding style
is checked with PHP_CodeSniffer, the sources are analysed with PHPStan and the code coverage is measured.

The feature tests send real requests through a Laravel application built by Orchestra Testbench, and the
`make:transformer` command is run and its output checked.

```bash
composer install

composer test           # run the test suite
composer test-coverage  # run the test suite and report code coverage
composer check-style    # check the coding style
composer fix-style      # fix the coding style where possible
composer analyse        # run static analysis
composer ci             # run all of the checks above
```

If you would rather not install PHP on your machine, the shipped `Dockerfile` and `Makefile` run everything inside a
container:

```bash
make test              # run the test suite
make coverage          # run the test suite and report code coverage
make check-style       # check the coding style
make fix-style         # fix the coding style where possible
make analyse           # run static analysis
make ci                # run all of the checks above
make shell             # open a shell inside the container
make help              # list every available target
```

Another PHP version can be used with `make test PHP_VERSION=8.5`.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/shetabit/transform-request.svg?style=flat-square
[ico-download]: https://img.shields.io/packagist/dt/shetabit/transform-request.svg?color=%23F18&style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-tests]: https://img.shields.io/github/actions/workflow/status/shetabit/transform-request/tests.yml?branch=master&label=Tests&style=flat-square
[ico-code-style]: https://img.shields.io/github/actions/workflow/status/shetabit/transform-request/code-style.yml?branch=master&label=Code%20Style&style=flat-square
[ico-static-analysis]: https://img.shields.io/github/actions/workflow/status/shetabit/transform-request/static-analysis.yml?branch=master&label=Static%20Analysis&style=flat-square
[ico-coverage]: https://img.shields.io/codecov/c/github/shetabit/transform-request/master?label=Coverage&style=flat-square

[link-packagist]: https://packagist.org/packages/shetabit/transform-request
[link-actions]: https://github.com/shetabit/transform-request/actions
[link-tests]: https://github.com/shetabit/transform-request/actions/workflows/tests.yml
[link-code-style]: https://github.com/shetabit/transform-request/actions/workflows/code-style.yml
[link-static-analysis]: https://github.com/shetabit/transform-request/actions/workflows/static-analysis.yml
[link-coverage]: https://codecov.io/gh/shetabit/transform-request
[link-author]: https://github.com/khanzadimahdi
[link-contributors]: ../../contributors