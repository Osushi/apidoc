## apidoc
Generate markdown documentation from your code.

## Requirements

- `php` >=7.2.5 || ~8.0.0

## Installation

```bash
$ composer require --dev osushi/apidoc
```

## Usage examples

### Script

#### 1. Create script file
```bash
$ mkdir docs
$ touch apidoc.php
```

#### 2. Edit code
```php
<?php
require_once '../vendor/autoload.php';

use Osushi\Apidoc\Apidoc;
use Osushi\Apidoc\Parameter;
use Osushi\Apidoc\Request;
use Osushi\Apidoc\Response;

Apidoc::init();
$apiDoc = Apidoc::getInstance();

$parameter = new Parameter();
$parameter->add('name', ['isa' => 'string', 'required' => true, 'comment' => 'user name', 'except' => ['bob', 'john']]);
$parameter->add('status', ['isa' => 'numric', 'default' => '10', 'comment' => 'user status', 'only' => ['10', '20', '30']]);
$parameter->note('Here is note1');
$parameter->note('Here is note2');

$apiDoc->record(
    'users:/users:GET', # This key format is {filename}:{path}:{method}
    $parameter,
    'Get All Users' # It's able to add comment
);

$request = new Request([
    'method' => 'GET',
    'path' => '/users',
    'parameters' => ['status' => 10, 'name' => 'tarou'],
    'headers' => ['Content-Type' => 'application/json'],
]);

$response = new Response([
    'code' => 200,
    'headers' => ['Content-Type' => 'application/json; charset=utf-8'],
    'body' => '{"id": 1,"name": "tarou","status": 10,"created_at": "2015-04-21T14:55:09.351Z","updated_at": "2015-04-21T14:55:09.351Z"}',
]);

$documents->example(
    'users:/users:GET',  # This key format is {filename}:{path}:{method}
    $request,
    $response,
    '200 Success' # It's able to add comment
);

$apiDoc->render();
```

#### 3. Run apidoc
```bash
$ php apidoc.php APIDOC
$ tree docs
docs
├── toc.md
└── users.md
```

Here are examples
* [toc.md](examples/toc.md)
* [users.md](examples/users.md)

:warning: If you want to render documents, please run script with `APIDOC` params.

### Integration to phpunit

#### 1. Initialize apidoc on bootstrap.php
```php
<?php
require_once '../vendor/autoload.php';

use Osushi\Apidoc\Apidoc;

Apidoc::init();

register_shutdown_function(function(){
   $apiDoc = Apidoc::getInstance();
   $apiDoc->render();
})
```

#### 2. Write your feature test.
```php
<?php

use Tests\TestCase;
use Osushi\Apidoc\Apidoc;
use Osushi\Apidoc\Parameter;
use Osushi\Apidoc\Request;
use Osushi\Apidoc\Response;

class UserIndexTest extends TestCase
{
    public static $apiDoc;

    public static function setUpBeforeClass()
    {
        # Set Parameter Details
        $parameter = new Parameter();
        $parameter->add('name', ['isa' => 'string', 'required' => true, 'comment' => 'user name', 'except' => ['bob', 'john']]);
        $parameter->add('status', ['isa' => 'numric', 'default' => '10', 'comment' => 'user status', 'only' => ['10', '20', '30']]);
        $parameter->note('here is note');

        self::$apiDoc = Apidoc::getInstance();
        self::$apiDoc->record(
            'users:/users:GET', # This key format is {filename}:{path}:{method}
            $parameter,
            'Get All Users' # Be able to add comment
        );
    }

   public function testIndex()
   {
       $params = [
           'status' => 10,
           'name' => 'tarou',
       ];
       $response = $this->call('GET', '/users', $params);
       $response->assertStatus(200);

       $request = new Request([
           'method' => 'GET',
           'path' => '/users',
           'parameters' => $params,
           'headers' => ['Content-Type' => 'application/json'],
       ]);

       $response = new Response([
           'code' => $response->getStatusCode(),
           'headers' => $response->getHeaders(),
           'body' => (string) $response->getBody(),
       ]);

       self::$apiDoc->example(
           'users:/users:GET',  # This key format is {filename}:{path}:{method}
           $request,
           $response,
           '200 Success' # It's able to add comment
       );
   }
}
```

#### 3. Run apidoc
```bash
$ APIDOC=1 phpunit
$ tree docs
docs
├── toc.md
└── users.md
```

## Documents

#### Initialization
```php
Apidoc::init($config);
```

#### Configuration
```php
use Osushi\Apidoc\Config;

Apidoc::init([Parameter.php
  Config::OUTPUT_PATH => 'yourdir',
  Config::TOC => false,
]);
```

+ Config::DOCUMENT_TEMPLATE_PATH - [String] twig template for each document (default: [document.md](templates/document.md))
+ Config::DOCUMENT_TOC_TEMPLATE_PATH - [String] twig template for ToC of docuement (default: [document.toc.md](templates/document.toc.md))
+ Config::DOCUMENT_TOC_TITLE - [String] ToC of document title (default: # Table of Contents)
+ Config::TOC_TEMPLATE_PATH - [String] twig template for ToC (default: [toc.md](templates/toc.md))
+ Config::TOC_TITLE - [String] ToC title (default: # Table of Contents);
+ Config::OUTPUT_PATH - [String] location to output files (default: ./docs);
+ Config::TOC - [Boolean] whether to generate toc.md (default: true);

#### Parameter
```php
use Osushi\Apidoc\Parameter;

$parameter = new Parameter();
$parameter->add('name', ['isa' => 'string', 'required' => true, 'comment' => 'user name', 'except' => ['bob', 'john']]);
$parameter->add('status', ['isa' => 'numric', 'default' => '10', 'comment' => 'user status', 'only' => ['10', '20', '30']]);
$parameter->note('here is note');
```

+ add(string $value, array $options) - Add parameters for documents
    + options.isa - string (e.g. string, integer)
    + options.required - boolean (e.g. true/false)
    + options.comment- string (e.g. comment)
    + options.format- string (e.g. Ymd)
    + options.except - array (e.g. ['bob', 'john'])
    + options.only - array (e.g. [10, 20])
+ note(string $node) - Add note for documents

#### Request
```php
use Osushi\Apidoc\Request;

$request = new Request([
    'method' => {string method},
    'path' => {string path},
    'parameters' => {array params},
    'headers' => {array headers},
]);
# or
$request = new Request();
$request = setMethod({string method});
$request = setPath({string method});
$request = setParameters({array params});
$request = setHeaders({array headers});
```

#### Response
```php
use Osushi\Apidoc\Response;

$response = new Response([
    'code' => {int status_code},
    'headers' => {array headers},
    'body' => {string json_body},
]);
# or
$response = new Response();
$response = setCode({int status_code});
$response = setHeaders({array headers});
$response = setBody({string json_body});
```

## License
MIT
