# Example of some cool use cases

## Default interface implementation
Imagine you have some code that connects to a server. A possible interface to define such server instance could be as follows:
```php
public interface Server
{
    /**
     * @return string
     */
    public function getHostname();
    
    /**
     * @return int
     */
    public function getPort();
}
```

Now you want to provide some default servers, so you could make this awesome Enum:
```php
public class DefaultServers extends GerritDrost\Lib\Enum\Enum implements Server
{
    const FOO = 0;
    const BAR = 1;
    
    protected function __FOO()
    {
        $this->hostname = 'foo.baz';
        $this->port = 80;
    }
    
    protected function __BAR()
    {
        $this->hostname = 'bar.baz';
        $this->port = 8080;
    }

    /**
     * @var string
     */
    private $hostname;
    
    /**
     * @var int
     */
    private $port;

    /**
     * @return string
     */
    public function getHostname()
    {
    
    }
    
    /**
     * @return int
     */
    public function getPort()
    {
    
    }
}
```

Now the user of the library can choose to provide his own Server implementation or use your default ones like so:
```php
$server1 = DefaultServers::FOO();

// MyServer implements Server, obviously
$server2 = new MyServer();

[... insert code that uses the Server interface ...]
```
