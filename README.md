# Vue Multiselect
This package provides methods to conveniently convert PHP classes to array data prepared for use on the frontend Vue component, <a href="vue-multiselect.js.org">Vue Multiselect</a>.
> Please note this utility package is independent of and not endorsed by https://vue-multiselect.js.org/.

## Table of Contents:
1. [Installation](#installation)
    - 1.1. [Laravel](#installation.laravel)
2. [The `Multiselectable` Trait](#the-multiselectable-trait)
    - 2.1. [`toMultiselectOption()`](#the-multiselectable-trait.to-multiselect-option)
3. [Eloquent Collection Methods](#eloquent-collection-methods)
    - 3.1. [`multiselect()`](#eloquent-collection-methods.multiselect)
    - 3.2. [`disbaleOptionsBy()`](#eloquent-collection-methods.disable-options-by)

<hr />

## <a name="installation"># 1.</a> Installation
Install using Composer:
```
$ composer require zachgilbert/vue-multiselect
```

Apply the trait `Multiselectable` to any PHP object (such as an Eloquent model, in Laravel) that you wish to convert to an option for use on the `<vue-multiselect />` Vue component:
```PHP
<?php

// ...
use ZachGilbert\VueMultiselectable\Traits\Multiselectable;

class User extends Model
{
    use Multiselectable;

    // ...
}
```

### <a name="installation.laravel"># 1.1.</a> Laravel
To extend Eloquent collections in laravel, be sure to register the following provider in `config/app.php`:

```PHP
<?php

return [
    // ...

    'providers' => [
        // ...

        \ZachGilbert\VueMultiselectable\Laravel\Providers\CollectionProvider::class,
    ],
];
```

<hr />

## <a name="the-multiselectable-trait"># 2.</a> The `Multiselectable` Trait

### <a name="the-multiselectable-trait.to-multiselect-option"># 2.1.</a> `toMultiselectOption()`
The `toMultiselectOption()` method converts any given class instance to an option formatted for use on the `<vue-multiselect />` Vue component.

#### Arguments:

##### `@param string $trackByAttribute`
The attribute on the class instance that should be used as the value for each item on the multiselect.

##### `@param string|null $labelAttribute`
The attribute on the class instance that should be used as the label for each item on the multiselect.

##### `@param string|null $trackByKey`
The key to assign a value to for each option on the multiselect.

##### `@param string|null $labelKey`
The key to assign a label to for each option on the multiselect.

##### `@param bool|null $isDisabled`
Whether the option for the multiselect should be disabled from being selected. If `null`, them if the object already has the property `$isDisabled` set then this value will be used.

<hr />

## <a name="eloquent-collection-methods"># 3.</a> Eloquent Collection Methods

Collections of PHP objects that implement the `Multiselectable` trait can be easily converted to a JSON-serializable array of options (just make sure the relevant provider is registered in `config/app.php`).

### <a name="eloquent-collection-methods.multiselect"># 3.1.</a> `multiselect()`

Converts each item in the collection to an array of options.

#### Arguments:

##### `@param string $trackByAttribute (default 'id')`
The attribute whose value from each item in the collection is used as options for the multiselect - default `'id'`.

Note that if this is the only argument provided, an array of values will be returned.
```PHP
<?php

$options = $collection->multiselect('name');

// ["John Smith", "Jane Doe"]
```

```HTML
<vue-multiselect :options="{{ $options->toJson() }}" />
```

##### `@param string|null $labelAttribute`
Whilst `$trackByAttribute` sets the values of items on the multiselect, it's common to want end-users to be shown some other label to represent this value, while keeping the value hidden for use elsewhere in code.

A second argument `$labelAttribute` determines the attribute from each collection item that should be used as the label.

```PHP
<?php

$options = $collection->multiselect('id', 'name');

// [
//     {"value": 1, "label": "John Smith"},
//     {"value": 2, "label": "Jane Doe"}
// ]
```

```HTML
<vue-multiselect :options="{{ $options->toJson() }}" />
```

##### `@param string|null $trackByKey`
By default, `vue-multiselect` assumes the value on each item in the JSON will be keyed by `"value"`.

`$trackByKey` will set the key to use for the value:

```PHP
<?php

$options = $collection->multiselect('id', 'name', 'user_id');

//  [
//     {"user_id": 1, "label": "John Smith"},
//     {"user_id": 2, "label": "Jane Doe"}
// ]
```

```HTML
<vue-multiselect :options="{{ $options->toJson() }}" track-by="user_id" />
```

##### `@param string|null $labelKey`
By default, `vue-multiselect` assumes the label on each item in the JSON will be keyed by `"label"`. `$labelKey` will set the key to use for the label:

```PHP
<?php

$options = $collection->multiselect('id', 'name', 'user_id', 'full_name');

// [
//     {"user_id": 1, "full_name": "John Smith"},
//     {"user_id": 2, "full_name": "Jane Doe"}
// ]
```

```HTML
<vue-multiselect :options="{{ $options->toJson() }}" track-by="user_id" label="full_name" />
```

### <a name="eloquent-collection-methods.disable-options-by"># 3.2.</a> `disbaleOptionsBy()`
The `disableOptionsBy()` method disables options for the multiselect based on a callback (the return value of which is either `true` or `false`).

#### Arguments:

##### `@param callable $callback`
Any items in the collection that pass a truth test as determined by the `$callable` callback will have `$isDisabled = true` set. Any items that fail will have `$isDisabled = false` set.

```PHP
<?php

$options = $collection->disableOptionsBy(function ($item, $key) {
    return strpos($item->name, 'John') === 0;
});

$options->multiselect('id', 'name');

// [
//     {"value": 1, "label": "John Smith", "$isDisabled": true},
//     {"value": 2, "label": "Jane Doe"}
// ]
```

> Note that disabling multiselect options is only applicable to options which can be converted to objects in JSON (i.e. arrays).
