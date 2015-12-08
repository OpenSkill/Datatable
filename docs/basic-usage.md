# Simple Datatable

The simplest `Datatable` you can create looks like this:
```php
$t = Datatable::make(new CollectionProvider(User::all())->build();

if ($t->shouldHandle()) {
    return $t->handleRequest();
}

return view('user-view', array('datatable' => $t->view()->build()));
```

and on the view side

```html 
{{
	$view->table();
}}
...
{{
	$view->script();
}}

```

In this example we do the following:

1. Get all users with `User::all()` 
2. Pass the user collection to a `CollectionProvider`
3. Build the `DatatableService` 

We then check if the `DatatableService` should handle the result and if so return the `$t->handleRequest()`.

**Please Note**: We are using the default coumns of the `User` model which means all columns by default.
On the view side we then just render the `table` and the `javascript` part

# More advanced example

A more sophicticated example could look like this:
```php
$t = Datatable::make(new CollectionProvider(User::all())
	->columns('id') // show the id column of the user model
	->columns('name') // also show the full name of the user
	->build();

if ($t->shouldHandle()) {
    return $t->handleRequest();
}

return view('user-view', array('datatable' => $t->view()->build()));
```

