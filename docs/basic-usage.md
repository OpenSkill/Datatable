# Basic Usage

## Simple Datatable

The simplest `Datatable` you can create looks like this:
```php
$t = Datatable::make(new CollectionProvider(User::all())
    ->build();

if ($t->shouldHandle()) {
    return $t->handleRequest();
}

return view('user-view', array('datatable' => $t));
```

and on the view side

user-view.blade.php
```html 
```

