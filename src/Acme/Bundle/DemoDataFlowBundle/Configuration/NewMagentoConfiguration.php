<?php
namespace Acme\Bundle\DemoDataFlowBundle\Configuration;

use Oro\Bundle\DataFlowBundle\Configuration\ConfigurationInterface;
use JMS\Serializer\Annotation\Type;

/**
 * Connector configuration
 *
 * @author    Nicolas Dupont <nicolas@akeneo.com>
 * @copyright 2012 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/MIT MIT
 *
 */
class NewMagentoConfiguration implements ConfigurationInterface
{

    /**
     * @Type("string")
     * @var string
     */
    public $driver = 'pdo_mysql';

    /**
     * @Type("string")
     * @var string
     */
    public $host = 'localhost';

    /**
     * @Type("string")
     * @var string
     */
    public $port;

    /**
     * @Type("string")
     * @var string
     */
    public $dbname;

    /**
     * @Type("string")
     * @var string
     */
    public $user;

    /**
     * @Type("string")
     * @var string
     */
    public $password;

    /**
     * @Type("string")
     * @var string
     */
    public $charset = 'UFT-8';

    /**
     * @Type("string")
     * @var string
     */
    public $prefix;

    /**
     * @return string
     */
    public function getDriver()
    {
        return $this->driver;
    }

    /**
     * @param string $driver
     * @return NewMagentoConfiguration
     */
    public function setDriver($driver)
    {
        $this->driver = $driver;

        return $this;
    }

    /**
     * @return string
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * @param string $host
     * @return NewMagentoConfiguration
     */
    public function setHost($host)
    {
        return $this->host = $host;

        return $this;
    }

    /**
     * @return string
     */
    public function getPort()
    {
        return $this->port;
    }

    /**
     * @param string $port
     * @return NewMagentoConfiguration
     */
    public function setPort($port)
    {
        $this->port = $port;

        return $this;
    }

    /**
     * @return string
     */
    public function getDbname()
    {
        return $this->dbname;
    }

    /**
     * @param string $name
     * @return NewMagentoConfiguration
     */
    public function setDbname($name)
    {
        $this->dbname = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param string $user
     * @return NewMagentoConfiguration
     */
    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param string $password
     * @return NewMagentoConfiguration
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return string
     */
    public function getCharset()
    {
        return $this->charset;
    }

    /**
     * @param string $charset
     * @return NewMagentoConfiguration
     */
    public function setCharset($charset)
    {
        $this->charset = $charset;

        return $this;
    }

    /**
     * @return string
     */
    public function getPrefix()
    {
        return $this->prefix;
    }

    /**
     * @param string $prefix
     * @return NewMagentoConfiguration
     */
    public function setPrefix($prefix)
    {
        $this->prefix = $prefix;

        return $this;
    }

}
