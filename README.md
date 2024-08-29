# laravel-model-extensions

## Installing

To install package use composer

    composer require codiliateur/laravel-model-extensions

## "Composite" Primary Keys

If your table has a composite primary key, you can create model from `\Codiliateur\LaravelModelExtensions\Database\Eloquent\CompositeKeyModel`

For example:

```php
namespace App\Models\Bookings;

use Codiliateur\LaravelModelExtensions\Database\Eloquent\CompositeKeyModel; 

class BoardingPass extends CompositeKeyModel
{
    protected $primaryKey = [
        'ticket_no',
        'flight_id',
    ];
}
```

To define **composite primary key** add property `$primaryKey` as array of key columns. 
Adding definition `$autoincrementing = false` is not required.

### Operating with composite key model

Now, to find any model using `find()`, you must specify a composite key value as an argument 
(an array of key column values) instead of a single scalar value.

    BoardingPass::find(['0005435189117', 198393])

To get multiple models using "find()" or "find Many()", specify an array of composite keys

    BoardingPass::find([["0005435189117", 198393], ["0005435189096", 198393]])

or

    BoardingPass::findMany([["0005435189117", 198393], ["0005435189096", 198393]])

To get a model's composite key use `getKey()`

```
    $boardingPass = BoardingPass::find(['0005435189117', 198393]);
    $boardingPass->getKey();
    
    > ['0005435189117',198393]
```

Other methods of the model work as before.