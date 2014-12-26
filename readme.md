Laravel Test Tools
==================

### Configuration File

Create the file "tests.php" in the config folder and add the following content. Adjust the settings.

```php
<?php

return [

    'mailcatcher' => [
        'url' => 'https://mailcatcher.money.dev',
        'port' => '443',
    ],

];
```

### Tests

To add one of the modules to a test class, the appropriate trait must be included. In addition the "setUp" and "tearDown" function of the module must be called in the "setUp" and "tearDown" functions of the test class.

```php
<?php

use Carbon\Carbon;
use Tests\Tools\DatabaseTestToolsTrait;

class ExampleTest extends TestCase {

    use DatabaseTestToolsTrait;

    /**
     * Setup the test environment.
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $this->setUpDatabaseTools();
    }

    /**
     * Clean up the testing environment before the next test.
     *
     * @return void
     */
    public function tearDown()
    {
        $this->tearDownDatabaseTools();

        parent::tearDown();
    }
```