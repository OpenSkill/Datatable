# Simple Datatable

The simplest `Datatable` you can create looks like this:
```php
$t = Datatable::make(new CollectionProvider(User::all())
	->column('name')
	->build();

if ($t->shouldHandle()) {
    return $t->handleRequest();
}

return view('user-view', array('datatable' => $t->view()));
```

and on the view side

```html 
{{
	$datatable->html();
}}

```

In this example we do the following:

1. Get all users with `User::all()` 
2. Pass the user collection to a `CollectionProvider`
3. Build the `DatatableService` 

We then check if the `DatatableService` should handle the result and if so return the `$t->handleRequest()`.

On the view side we just render the html and the javascript with the `html` method.

# More advanced example

A more sophicticated example could look like this:
```php
$t = Datatable::make(new CollectionProvider(User::all())
	->columns('id') // show the id column of the user model
	->columns('name', null, Searchable::NONE(), Orderable::NONE()) // also show the full name of the user, but do not allow searching or ordering of the column
	->build();

if ($t->shouldHandle()) {
    return $t->handleRequest();
}

return view('user-view', array('datatable' => $t->view()));
```

```html 
{{
	$datatable
		->headers() // tell the table to render the header in the table
		->columns('id', '#') // show # in the header instead of 'id'
		->columns('name', 'Full name') // show 'Full name' in the header instead of 'name'
		->table(); // render just the table
}}
{{
	$datatable
		->script() // now render the script
}

