<p align="center"><img src="https://www.interflora.co.uk/images/logo.svg" width="400"></p>

# Exercice 

We in Interflora Denmark, need to model how our company is structured so we can help our new employees have a better overview of our 
company structure.

We have our root node (only one, in our case the CEO) and several child nodes.
Each of these nodes may have its own children. 

It can be structured as something like this: 
```
        root
       /    \
      a      b
      |
      c
    / 	\
   d     e
```

# Requirements

- Laravel Valet
- PHP 8.0
- Composer
- MySQL
- SQLite (for automatic tests in memory)

# Install

Use Laravel Valet or similar with the public path to be `public/`.

Install composer dependencies.
```shell
composer install
```

Copy `.env.example` to `.env` and update the database credentials.

Migrate and seed the database:
```shell
php artisan migrate --seed
```

# API Documentation

## Add a new node to the tree.
Request:
```
curl --location --request POST '/api/nodes' \
--header 'Content-Type: application/json' \
--data-raw '{
    "name": "Awesome Node",
    "parent_id": 1,
    "programming_language": "php",
    "department": "Development"
}'
```

Response:
```
{
    "data": {
        "id": 2,
        "name": "Awesome Node",
        "department": "Development",
        "programming_language": "php",
        "parent_id": 1,
        "height": 2,
        "children": []
    }
}
```

## Get all child nodes of a given node from the tree. (Just 1 layer of children)
Request:
```
curl --location --request GET '/api/nodes/1'
```

Response:
```
{
    "data": {
        "id": 1,
        "name": "R00T",
        "department": null,
        "programming_language": null,
        "parent_id": 1,
        "height": 0,
        "children": [
            {
                "id": 2,
                "name": "a",
                "department": null,
                "programming_language": null
            },
            {
                "id": 3,
                "name": "b",
                "department": null,
                "programming_language": null
            }
        ]
    }
}
```

## Change the parent node of a given node.
Request:
```
curl --location --request PATCH '/api/nodes/3' \
--header 'Content-Type: application/json' \
--data-raw '{
    "parent_id": 1
}'
```

Response:
```
{
    "data": {
        "id": 3,
        "name": "Awesome Node",
        "department": null,
        "programming_language": null,
        "parent_id": 1,
        "height": 1,
        "children": []
    }
}
```

# Testing
This application is fully automatic tested.

```shell
/vendor/bin/phpunit

PHPUnit 9.5.20 #StandWithUkraine

............                                                      12 / 12 (100%)

Time: 00:00.273, Memory: 32.00 MB

OK (12 tests, 22 assertions)
```

# Questions

## Should the identifier be string-based?
We could easily convert the Node identifier to be string based in order to support the strings being like `root`, `a`,
`b` and so on. That might make sense in this case, but I wouldn't overcomplicate this
by during so if it wasn't intended.

## Should Nodes have roles?
From the description it sounds like there could be a use-case for roles, here including Developers and Managers
to define their access to the additional `department` and `programming_language` field.

## Only one root node?
Should we make a limit to allow only one root node to exists?

# Improve

What would I improve if I had more time.
I would clean-up the request classes and the tests even further.
I would add some database indexes and query improvements.
Add cache for Node's for better performance.
Add pagination and other API enhancements.
