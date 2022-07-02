# Laravel Vue Multiselect
This package contains a set of collection methods to easily prepare data for the frontend Vue component, <a href="vue-multiselect.js.org">Vue Multiselect</a>.
> Please note: this package is to prepare data independent of, and not endorsed by, https://vue-multiselect.js.org/.

1. [Installation](#installation)
2. [Usage](#usage)
3. [Collection Methods](#collection-methods)
    - 3.1. [`multiselect()`](#collection-methods.multiselect)
    - 3.2. [`disbaleOptionsBy()`](#collection-methods.disable-options-by)
4. [Query Builder Methods](#query-builder-methods)

## <a name="installation"># 1.</a> Installation
```
$ composer require zachgilbert/laravel-vue-multiselect
```

## <a name="usage"># 2.</a> Usage
Register providers in `config/app.php`:
```PHP
<?php

return [
    // ...

    'providers' => [
        // ...

        \Zachgilbert\LaravelVueMultiselect\Providers\CollectionProvider::class,
    ],
];
```

## <a name="collection-methods"># 3.</a> Collection Methods

### <a name="collection-methods.vue-multiselect"># 3.1.</a> `multiselect()`

The `multiselect` method plucks values form a collection and returns JSON for the `vue-multiselect` options.

#### Arguments
##### `$trackByAttribute (string, 'id')`
The attribute whose value from each item in the collection is used as options for the multiselect - default `'id'`.

Note that if this is the only argument provided, a JSON array of values will be returned:

```PHP
<?php

$options = $collection->multiselect('name');

// '["John Smith", "Jane Doe"]'
```

```HTML
<vue-multiselect :options="{{ $options }}" />
```

##### `$labelAttribute (string, null)`
Whilst `$trackByAttribute` sets the values of items on the multiselect, it's common to want end-users to be shown some other label to represent this value, while keeping the value hidden for use elsewhere in code.

A second argument `$labelAttribute` determines the attribute from each collection item that should be used as the label.

```PHP
<?php

$options = $collection->multiselect('id', 'name');

// [
//     {"value": "1", "label": "John Smith"},
//     {"value": "2", "label": "Jane Doe"}
// ]
```

```HTML
<vue-multiselect :options="{{ $options }}" />
```

##### `$trackByKey (string, null)`
By default, `vue-multiselect` assumes the value on each item in the JSON will be keyed by `"value"`.

`$trackByKey` will set the key to use for the value:

```PHP
<?php

$options = $collection->multiselect('id', 'name', 'user_id');

// [
//     {"user_id": "1", "label": "John Smith"},
//     {"user_id": "2", "label": "Jane Doe"}
// ]
```

```HTML
<vue-multiselect :options="{{ $options }}"
    track-by="user_id" />
```

##### `$labelKey (string, null)`
By default, `vue-multiselect` assumes the label on each item in the JSON will be keyed by `"label"`.

`$labelKey` will set the key to use for the label:

```PHP
<?php

$options = $collection->multiselect('id', 'name', 'user_id', 'full_name');

// [
//     {"user_id": 1, "full_name": "John Smith"},
//     {"user_id": 2, "full_name": "Jane Doe"}
// ]
```

```HTML
<vue-multiselect :options="{{ $options }}"
    track-by="user_id"
    label="full_name" />
```

### <a name="collection-methods.disable-options-by"># 3.2.</a> `disbaleOptionsBy()`
The `disableOptionsBy()` method applies `$isDisabled = true` to each item in the collection that passes a given truth test as determined by the callback provided. The collection is then returned.

```PHP
<?php

$options = $collection->disableOptionsBy(function ($item, $key) {
    return substr($item->name, 0) === 'John';
});

$options->multiselect('id', 'name');

// [
//     {"value": 1, "label": "John Smith", "$isDisabled": true},
//     {"value": 2, "label": "Jane Doe"}
// ]
```

> Note that disabling multiselect options is only applicable to object-formatted options in the options array.

## <a name="query-builder-methods"># 4.</a> Query Builder Methods

## Raw PHP Arrays
