Slice your test suite in chunks to run it in parallel.

## Usage

Install phpunit-slicer using Composer:

```
composer require wizaplace/phpunit-slicer
```

Instead of running your test suite with `vendor/bin/phpunit`, run phpunit-slicer instead:

```
vendor/bin/phpunit-slicer --slices 1/2
```

The `--slices` allows to define how many slices to use and which one to run. For example `1/2` means that the test suite will be split in 2, and only the first slice will be run.

PHPUnit-slicer is mainly useful for continuous integration: it allows to run a large test suite in parallel accross several jobs. To enable this, simply replace your single PHPUnit job with 2 (or more) jobs:

- `vendor/bin/phpunit-slicer --slices 1/2`
- `vendor/bin/phpunit-slicer --slices 2/2`
