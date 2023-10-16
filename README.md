# About
Cody is a PHP package for generating PHP files.

## Requirements
This library requires PHP >= 7.4.

# Installing

## Composer
1. Run `composer require jeffpacks/cody` in your shell

## Download
If you've downloaded or received the `jeffpacks/cody` package manually, you may place it anywhere in your file system and call `require_once('/path/to/cody/autoload.php')` in your code to start using it.

# Concept
Cody lets you create a code "project" and populate it with PHP classes, interfaces and traits. Once your code has created the project, the project can be exported to the file system as a codebase.

In Cody, namespaces are the starting point for creating classes, interfaces, traits and sub-namespaces.

## Examples
The `jeffpacks\cody\Cody::createProject()` method creates and returns a new `Project` instance.

```php
<?php
require_once('vendor/autoload.php'); # Using Composer to load Cody

use jeffpacks\cody\Cody;

# Create project
$project = Cody::createProject('', 'acme\\webshop'); # Gotta escape them backslashes
$projectNamespace = $project->getNamespace(); # This is the project namespace, "acme\webshop"

$interfaces = $projectNamespace->createNamespace('interfaces'); # We'll put our interfaces in the "acme\webshop\interfaces" namespace
$customer = $interfaces->createInterface('Customer');
$customer->setDescription('Represents a web-shop customer');
$customer->createMethod('getName')->setDescription('Provides the full name of the customer')->setReturnTypes('string');
$customer->createMethod('getAddress')->setDescription('Provides the postal address of the customer')->setReturnTypes('string');

$user = $interfaces->createInterface('User');
$user->setDescription('Represents a web-shop user');
$user->createMethod('getUsername')->setDescription('Provides the username of the user')->setReturnTypes('string');
$user->createMethod('getPasswordHash')->setDescription('Provides the hashed password of the user')->setReturnTypes('string');

$client = $projectNamespace->createClass('Client')->setDescription('Represents a web-shop client');
$client->implement($customer);
$client->implement($user);

$client->createVariable('name', 'string|null'); # pipe style
$client->createVariable('address', '?string'); # nullable style
$client->createVariable('username', ['string', 'null']); # array style
$client->createVariable('passwordHash', '?string');

$client->getMethod('getName')->setBody('return $this->name;')
$client->getMethod('getAddress')->setBody('return $this->address;')
$client->getMethod('getUsername')->setBody('return $this->username;')
$client->getMethod('getPasswordHash')->setBody('return $this->passwordHash;')

$project->export()->toDirectory('/tmp/')->run();
```

## Authors
* Johan Fredrik Varen – [github.com/jeffpacks](https://github.com/jeffpacks)

## License
Cody is the proprietary work of and property of Johan Fredrik Varen. Copyright Johan Fredrik Varen 2023.