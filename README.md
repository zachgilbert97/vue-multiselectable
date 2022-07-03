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
4. [Eloquent Query Builder Methods](#eloquent-query-builder-methods)
    - 4.1. [`multiselect()`](#eloquent-query-builder-methods.multiselect)

## <a name="installation"># 1.</a> Installation
Install using Composer:
```
$ composer require zachgilbert/vue-multiselect
```

Apply the trait `Multiselectable` to any PHP object (such as an Eloquent model, in Laravel) that you wish to convert to an option for use on the `<vue-multiselect />` Vue component:
```PHP
<?php

use ZachGilbert\VueMultiselectable\Traits\Multiselectable;

class User
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

		\ZachGilbert\VueMultiselectable\Laravel\Providers\MultiselectProvider::class,
    ],
];
```

## <a name="the-multiselectable-trait"># 2.</a> The `Multiselectable` Trait

### <a name="the-multiselectable-trait.to-multiselect-option"># 2.1.</a> `toMultiselectOption()`
The `toMultiselectOption()` method converts any given class instance to an option formatted for use on the `<vue-multiselect />` Vue component.

`toMultiselectOption()` accepts five arguments: `$trackByAttribute`, `$labelAttribute`, `$trackByKey`, `$labelKey`, and `$isDisabled`.

- `$trackByAttribute` and `$labelAttribute` are the attributes on the class that should be used for the value and the label on the multiselect option:
	```PHP
	<?php
	$user->toMultiselectOption('id', 'name');
	// ['value' => 1, 'name' => 'John McEnroe']
	```
	Note that `$trackByAttribute` is the only required argument. Provided on its own, `->toMultiselectOption()` will just return the attribute value:
	```PHP
	<?php
	$user->toMultiselectOption('name');
	// 'John McEnroe'
	```
- `$trackByKey` and `$labelKey` set how the attributes should be keyed:
	```PHP
	<?php
	$user->toMultiselectOption('id', 'name', 'user_id', 'user_name');
	// ['user_id' => 1, 'user_name' => 'John McEnroe']
	```
- `$isDisabled`, if provided, will set a key with a boolean value for whether the option should be disabled:
	```PHP
	<?php
	$user->toMultiselectOption('id', 'name', 'value', 'label', true);
	// ['value' => 1, 'name' => 'John McEnroe', '$isDisabled' => true]
	```

## <a name="eloquent-collection-methods"># 3.</a> Eloquent Collection Methods

In Larvel, collections of PHP objects that use the `Multiselectable` trait can be easily converted to a JSON-serializable array of options.
>Make sure to register the provider `MultiselectProvider` in `config/app.php`).

### <a name="eloquent-collection-methods.multiselect"># 3.1.</a> `multiselect()`
The `multiselect()` method converts each item in the collection to an array of options.

`multiselect()` accepts four arguments: `$trackByAttribute`, `$labelAttribute`, `$trackByKey`, and `$labelKey`:
- `$trackByAttribute` and `$labelAttribute` are the attributes on each item in the collection that should be used for the value and the label on the multiselect option:
	```PHP
	<?php
	$collection->multiselect('id', 'name')->toJson();
	// '[{"value": 1, "name": "John McEnroe"}, {"value": 2, "name": "Dr Eggman"}]''
  ```
	Note that if only the first argument is provided, the resultant collection will be an array of that attribute's values:
	```PHP
	<?php
	$collection->multiselect('name')->toJson();
	// '["John McEnroe", "Dr Eggman"]'
	```
- By default, the Vue multiselect component assumes the value and label on each option will be keyed by `'value'` and `'label'`. Using `$trackByKey` and `$labelKey` can set how the attributes should be keyed:
	```PHP
	<?php
	$collection->multiselect('id', 'name', 'user_id', 'user_name')->toJson();
	// '[{"user_id": 1, "user_name": "John McEnroe"}, {"user_id": 2, 	"user_name": "Dr Eggman"}]'
	```

### <a name="eloquent-collection-methods.disable-options-by"># 3.2.</a> `disbaleOptionsBy()`
The `disableOptionsBy()` method disables options for the multiselect based on a callback (the return value of which is evaluated as `true` or `false`). Any items that pass the truth test will have `$isDisabled = true` set; any items that fail will have `$isDisabled = false` set.
```PHP
<?php

$options = $collection->disableOptionsBy(function ($item, $key) {
    return strpos($item->name, 'John') === 0;
});

$options->multiselect('id', 'name')->toJson();

// '[{"value": 1, "label": "John McEnroe", "$isDisabled": true}, {"value": 2, "label": "Dr Eggman"}]'
```

> Note that disabling multiselect options is only applicable to options which can be converted to objects in JSON (i.e. arrays).

## <a name="eloquent-query-builder-methods"># 4.</a> Eloquent Query Builder Methods
Using Laravel's Eloquent query builder, we can obtain database records in a multiselect-ready format. Note this does not require any classes using the `Multiselectable` trait.

### <a name="eloquent-query-builder-methods.multiselect"># 4.1.</a> `multiselect()`
The `multiselect()` method selects two columns, with aliases, ready for use on the multiselect component. It accepts four arguments: `$trackBySelect`, `$labelSelect`, `$trackByAlias`, and `$labelAlias`:
- `$trackBySelect` and `$labelSelect` are the raw SQL columns/statements to select, that should be used for the value and the label on the multiselect:
	```PHP
	<?php

	$users = User::query()
        ->multiselect('users.id', 'CONCAT(users.name, " - ", companies.name)')
        ->join('companies', 'users.company_id', '=', 'companies.id');

	// SELECT `users`.`id` AS `value`, CONCAT(`users`.name`, " - ", `companies`.`name`) AS `label`
	// ...

	$users->get()->toJson();

	// '[{"value": "1", "label": "John McEnroe - Tennis Co."}]'
	```
- `$trackByAlias` and `$labelAlias` set the column names for the selects:
	```PHP
	<?php

	$users = User::query()
        ->multiselect(
            'users.id', 'CONCAT(users.name, " - ", companies.name)',    'employee_id', 'employee_name'
        )
        ->join('companies', 'users.company_id', '=', 'companies.id');

	// SELECT `users`.`id` AS `employee_id`, CONCAT(`users`.name`, " - ", `companies`.`name`) AS `employee_name`
	// ...

	$users->get()->toJson();

	// '[{"employee_id": 1, "employee_name": "John McEnroe - Tennis Co."}]'
	```
