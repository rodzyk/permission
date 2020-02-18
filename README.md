# Raisins Permission Validation

Simple PHP library for Permission Validation

## Composer Installation

1. Get [Composer](http://getcomposer.org/)
2. Require Raisins Validation with `composer require raisins/permission`
3. Add the following to your application's main PHP file: `require 'vendor/autoload.php';`

## Usage

```php
use\Raisins\{PermissionValidation, Permission};

$pv = new PermissionValidation();

// set required permissions
$pv->required = [
    new Permission("read", -1),
    new Permission("edit"),
    new Permission("delete", 1)
];

// set available permission
$pv->available = [
    new Permission("read"),
    new Permission("edit"),
    new Permission("delete", -1)
];

$result = $pv->validate();

echo $result; // false

// merge overridden permissions (option)
$pv->merge([
    new Permission("delete", 1)
]);

$result = $pv->validate();

echo $result; // true
```

## Set by JSON

```php
$pv = new PermissionValidation();

$permissionsJson = '[{"name": "read", "state": -1}, {"name": "edit", "state": 0}, {"name": "delete", "state": 1}]';

$pv->setAvailable($permissionsJson);
$pv->setRequired($permissionsJson);

// ...

```