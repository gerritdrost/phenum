# Example usage

## Default interface implementation
Although it doesn't make sense, imagine for some reason you think `psr/log` is too complicated and need your own Logger interface.
```php
interface Logger
{
    /**
     * @param string $message
     */
    public function log($message);

    /**
     * @return string
     */
    public function getName();
}
```

Now you want to provide some default servers, so you could make this awesome Enum:
```php
/**
 * @method static DefaultLogger ECHOLOG()
 * @method static DefaultLogger KITTEN()
 */
class DefaultLogger extends GerritDrost\Lib\Enum\Enum implements Logger
{
    const ECHOLOG = 0;
    const KITTEN = 1;

    protected function __ECHOLOG()
    {
        $this->name = 'Echo';
        $this->logCallable = function($message) {
            echo "$message\n";
        };
    }

    protected function __KITTEN()
    {
        $this->name = 'Because kittens';
        $this->logCallable = function($message) {
            echo "I iz adorable!\n";
        };
    }

    /**
     * @var string
     */
    private $name;

    /**
     * @var callable
     */
    private $logCallable;

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return int
     */
    public function log($message)
    {
        $logCallable = $this->logCallable;
        $logCallable($message);
    }
}
```

Now the user of the library can choose to provide his own Server implementation or use your default ones like so:
```php
/* @var Logger[] $loggers */
$loggers = [ DefaultLogger::KITTEN(), DefaultLogger::ECHOLOG() ];

$message = 'foobar';

foreach ($loggers as $logger) {
    $logger->log($logger->getName());
    $logger->log($message);
}
```
