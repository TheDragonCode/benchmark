# Runtime Comparison

![the dragon code runtime comparison](https://preview.dragon-code.pro/the-dragon-code/runtime-comparison.svg?brand=php&invert=1)

## Using

```php
use DragonCode\RuntimeComparison\Comparator;

(new Comparator())->compare(
    fn () => sleep(1),
    fn () => sleep(1),
);

(new Comparator())->compare([
    fn () => sleep(1),
    fn () => sleep(1),
]);

(new Comparator())->compare([
    'foo' => fn () => sleep(1),
    'bar' => fn () => sleep(1),
]);
```

### Iterations Count

By default, the comparator performs 10 iterations per callback, but you can change this number by calling the `iterations` method:

```php
use DragonCode\RuntimeComparison\Comparator;

(new Comparator())
    ->iterations(20)
    ->compare(
        fn () => sleep(1),
        fn () => sleep(1),
    );
```

If the passed value is less than 1, then one iteration will be performed for each callback.
