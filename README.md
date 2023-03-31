# Chuck Norris Jokes

Create Chuck norris jokes in your PHP project

## Installation

Use the package manager composer to install this package.

```bash
composer require victorycodedev/chuck-norris-jokes
```

## Usage

```php
use Victorycodedev\ChuckNorrisJokes\Jokefactory;

$jokes = new Jokefactory();
$joke = $jokes->getRandomJoke();
```

## Contributing

Pull requests are welcome. For major changes, please open an issue first
to discuss what you would like to change.

Please make sure to update tests as appropriate.

## License

[MIT](./LICENSE.md)
