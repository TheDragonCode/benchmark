# Upgrading

## From 3 to 4

### High Impact Changes

#### The `start` Method Renamed To `make`

The static factory method `start` has been renamed to `make`:

```php
// Before
Benchmark::start()

// After
Benchmark::make()
```

#### The `prepare` Method Renamed To `beforeEach`

The `prepare` method has been renamed to `beforeEach`:

```php
// Before
Benchmark::start()
    ->prepare(function (string $name, int $iteration) {
        // ...
    })

// After
Benchmark::make()
    ->beforeEach(function (string $name, int $iteration) {
        // ...
    })
```

#### The `compare` Method No Longer Outputs Results Directly

The `compare` method now returns `static` instead of `void`. To get results, you must explicitly call one of the output methods:

- `toConsole()` — prints the result table to the console
- `toData()` — returns an array of `ResultData` objects
- `toAssert()` — returns an `AssertService` instance for assertions

```php
// Before
Benchmark::start()
    ->compare(
        fn () => /* ... */,
        fn () => /* ... */,
    );

// After
Benchmark::make()
    ->compare(
        fn () => /* ... */,
        fn () => /* ... */,
    )
    ->toConsole();
```

#### The `withoutData` Method Has Been Removed

The `withoutData` method has been removed without replacement.

#### Fluent Methods Now Return `static` Instead Of `self`

All fluent methods (`iterations`, `round`, `beforeEach`, `afterEach`, `before`, `after`, `compare`, `toConsole`) now return `static` instead of `self` for better extensibility.

### Medium Impact Changes

#### The `Transformer` Contract Has Been Removed

The `DragonCode\Benchmark\Contracts\Transformer` interface has been removed. If you implemented this interface, use the new `ResultTransformer` class instead.

#### The `ValueIsNotCallableException` Constructor Changed

The exception now accepts a `mixed` value (the actual object) instead of a `string` type name:

```php
// Before
throw new ValueIsNotCallableException(gettype($value));

// After
throw new ValueIsNotCallableException($value);
```

#### Dependencies Changed

- Removed `dragon-code/support` dependency
- Removed `symfony/console` dependency
- Added `symfony/polyfill-php85` dependency

If your project relied on `dragon-code/support` or `symfony/console` being pulled in transitively, you must now require them directly.

### Low Impact Changes

#### Renamed Service Classes

All service classes now have a `Service` suffix:

| Before (3.x)                | After (4.x)                        |
|-----------------------------|------------------------------------|
| `Services\Arr`              | Removed                            |
| `Services\MeasurementError` | `Services\MeasurementErrorService` |
| `Services\Memory`           | `Services\MemoryService`           |
| `Services\Runner`           | `Services\RunnerService`           |
| `Services\View`             | `Services\ViewService`             |

#### Renamed View Classes

All view classes now have a `View` suffix:

| Before (3.x)       | After (4.x)            |
|--------------------|------------------------|
| `View\ProgressBar` | `View\ProgressBarView` |
| `View\Table`       | `View\TableView`       |

#### Renamed Transformer Classes

The old transformer classes (`Base`, `Separator`, `Stats`, `Times`, `Transformer`, `Winner`) have been removed and replaced with a single `ResultTransformer` class.

#### The `ArrayService` Class Has Been Removed

The `DragonCode\Benchmark\Services\Arr` class has been removed without replacement.

#### New `Data` Classes

New DTO classes have been introduced:

- `DragonCode\Benchmark\Data\ResultData`
- `DragonCode\Benchmark\Data\MetricData`

#### New Callback Methods

New lifecycle callback methods have been added:

- `before(Closure $callback)` — called once before each named callback group
- `after(Closure $callback)` — called once after each named callback group
- `afterEach(Closure $callback)` — called after each iteration

#### The `ram` Terminology Replaced With `memory`

All internal references to `ram` have been renamed to `memory` for consistency.

#### The `assert` Method Renamed to `toAssert`

The `assert` method has been renamed to `toAssert` and now returns an `AssertService` instance instead of `static`.
