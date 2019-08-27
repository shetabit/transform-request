
<p align="center">
    <img src="resources/images/diagram.png?raw=true">
</p>



# Transform laravel requests

you can **normalize** or **change request data structure** with transformers.

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

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[link-packagist]: https://packagist.org/packages/shetabit/transform-request
[link-code-quality]: https://scrutinizer-ci.com/g/shetabit/transform-request
[link-author]: https://github.com/khanzadimahdi
[link-contributors]: ../../contributors