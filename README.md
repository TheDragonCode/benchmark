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
    'foo' => fn () => sleep(1),
    'bar' => fn () => sleep(1),
]);

(new Comparator())
    ->iterations(20)
    ->compare(
        fn () => sleep(1),
        fn () => sleep(1),
    );
```
