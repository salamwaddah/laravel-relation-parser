[![Latest Version on Packagist](https://img.shields.io/packagist/v/salamwaddah/laravel-relation-parser.svg?style=flat-square)](https://packagist.org/packages/salamwaddah/laravel-relation-parser)
[![Total Downloads](https://img.shields.io/packagist/dt/salamwaddah/laravel-relation-parser.svg?style=flat-square)](https://packagist.org/packages/salamwaddah/laravel-relation-parser)
[![MIT Licensed](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)

## Installation

```bash
composer require salamwaddah/laravel-relation-parser
```

| Version | Laravel  | PHP    |
|---------|----------|--------|
| 1.x     | 10, 11   | >= 8.1 |
| 0.3.x   | 8, 9, 10 | >= 7.3 |

## Usage

Allow your application to load any model relation in the response without the need of changing your API controllers.

Pass a comma separated relations to your http request.

### Loading relations

Add a `with` param to your HTTP request

`/users?with=orders,posts,anyOtherRelation`

### Loading relation counts

Add a `with_count` param to your HTTP request

`/users?with_count=orders,anyOtherRelation,anotherRelationToCount,blaBlaBla`

### Loading relations and counts

`/users?with=orders&with_count=orders`

### In your controller

```php
use SalamWaddah\RelationParser\LoadsRelations;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    use LoadsRelations;
    
    public function index(Request $request)
    {
            $users = User::query()
                // .. your query logic here
                ->get();
    
            // this line adds the relations/counts
            $this->loadRelations($users, $request);
    
            // return your results however you like
            return response()->json($users);
        }
    }
```

## Response example

```json
[
  {
    "id": 1,
    "name": "Salam",
    "orders_count": 2,
    "orders": [
      {
        "id": 2,
        "product": "something",
        "price": 100
      },
      {
        "id": 1,
        "product": "something else",
        "price": 150
      }
    ]
  },
  {
    "id": 2,
    "name": "Naren",
    "orders_count": 1,
    "orders": [
      {
        "id": 3,
        "product": "something",
        "price": 100
      }
    ]
  }
]
```

## Customize

If `with` or `with_count` params are used for something else in your application then you can customize those params
in `loadRelations()` method.
> $this->loadRelations($users, $request, 'customWithParam', 'custom_with_count_param');

## Testing

```bash
composer test
```
