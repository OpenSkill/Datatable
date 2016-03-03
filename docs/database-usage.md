# Datatable Usage
## Using QueryBuilderProvider

`QueryBuilderProvider` is a provider for `Datatable` that allows pulling data for a Datatable outside of a Laravel Query Builder.

Similar to the `CollectionProvider`, the simplest `Datatable` you can create looks like this:

```php
$t = Datatable::make(new QueryBuilderProvider(DB::table('data')))
    ->column('name')
    ->build();

if ($t->shouldHandle()) {
    return $t->handleRequest();
}

return view('user-view', array('datatable' => $t->view()));
```

and on the view side

```
{{
    $datatable->html();
}}
```

In this example we do the following:

1. Create an instance of `Illuminate\Database\Query\Builder` with DB::table()
2. Pass the instance to a QueryBuilderProvider
3. Build the DatatableService

We then check if the `DatatableService` should handle the result and if so return the `$t->handleRequest()`.

On the view side we just render the html and the javascript with the html method.

## Modifying queries before preparing Datatable

One of the benefits of passing an instance of `Illuminate\Database\Query\Builder` is that we can change the query that is passed to the `QueryBuilderProvider` before the Datatable is handled.

This means that we could hide particular data from a Datatable. This is handy for many use cases, for example, we might not want to show users that have been deleted.

```php
$query = DB::table('users')
            ->where('activated', '=', 1)
            ->where('deleted', '=', 0);

$t = Datatable::make(new QueryBuilderProvider($query))
    ->column('name')
    ->build();

if ($t->shouldHandle()) {
    return $t->handleRequest();
}
```

## Getting data out of MongoDB

If you install `jenssegers/mongodb`, QueryBuilderParser will also be able to pull data out of MongoDB using jenssegers' MongoDB Query Builder!

```
$query = DB::collection('data');

$t = Datatable::make(new QueryBuilderProvider($query))
    ->column('name', 'title', 'description')
    ->build();

if ($t->shouldHandle()) {
    return $t->handleRequest();
}
```

