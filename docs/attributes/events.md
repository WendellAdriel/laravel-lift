# Events

**Lift** provides three attributes to help you manage your model's **events**.

## Listener

The `Listener` attribute allows you to register a listener function for model events.

A more convenient way of registering a listener function "replacing" laravels event closures for eloquent models, more info [Laravel Docs: Eloquent Closures](https://laravel.com/docs/10.x/eloquent#events-using-closures)

```php
use Illuminate\Database\Eloquent\Model;
use WendellAdriel\Lift\Attributes\Events\Listener;
use WendellAdriel\Lift\Lift;

final class Product extends Model
{
    use Lift;


    #[Listener]
    public function onCreated(Product $product) {
    	Log::info("Product {$product->name} has been created.");
    }
}
```

## API

```php
#[Listener(event: 'created', queue: true)]
```

`event` needs to be one of Laravel's model event e.g: 'created', 'creating', 'updated'. If you set `queue` to true your handler will be executed async by Laravel's queue system. 

> ⚠️ **If your function name is equal to the event name** prefixed with "on" like onSaving or onDelete you don't need to specify the event name with the `Listener` Attribute

## Observer

The `Observer` attribute allows you to register a observer class for model events.

This is used to register a Observer Class with a model explained in more detail here: [Laravel Docs: Eloquent Observers](https://laravel.com/docs/10.x/eloquent#observers)

```php
use Illuminate\Database\Eloquent\Model;
use WendellAdriel\Lift\Attributes\Events\Observer;
use WendellAdriel\Lift\Lift;


#[Observer(ProductObserver::class)]
final class Product extends Model
{
    use Lift;

}
```

## API

```php
#[Observer(string $observer)]
```

With this attribute you can register a [`observer`](https://laravel.com/docs/10.x/eloquent#observers) to your model, in theory you could register as many observer classes as you want.

## Dispatches

The `Dispatches` attribute allows you to dispatch custom [Laravel Events](https://laravel.com/docs/10.x/events#defining-events).


```php
use Illuminate\Database\Eloquent\Model;
use WendellAdriel\Lift\Attributes\Events\Observer;
use WendellAdriel\Lift\Lift;


#[Dispatches(ProductSaved::class)]
#[Dispatches(ProductHasBeenSetup::class, 'created')]
final class Product extends Model
{
    use Lift;

}
```

## API

```php
#[Dispatches(string $eventClass, string $event = '')]
```

`event` needs to be one of Laravel's model event e.g: 'created', 'creating', 'updated', but is optional if your [`eventClass`](https://laravel.com/docs/10.x/events#defining-events) contains the event string in its name like `ProductSaved`, `saved` will be interpreted as the event.
