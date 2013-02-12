<?php
namespace Acme\Bundle\DemoDataFlowBundle\Configuration;

use Oro\Bundle\DataFlowBundle\Configuration\AbstractConfiguration;
use JMS\Serializer\Annotation\Type;

/**
 * Connector configuration
 *
 * @author    Nicolas Dupont <nicolas@akeneo.com>
 * @copyright 2012 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/MIT MIT
 *
 */
class MagentoConfiguration extends AbstractConfiguration
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
    public $tablePrefix;

    /**
     * @return string
     */
    public function getDriver()
    {
        return $this->driver;
    }

    /**
     * @param string $driver
     *
     * @return MagentoConfiguration
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
     *
     * @return MagentoConfiguration
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
     *
     * @return MagentoConfiguration
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
     *
     * @return MagentoConfiguration
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
     *
     * @return MagentoConfiguration
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
     *
     * @return MagentoConfiguration
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
     *
     * @return MagentoConfiguration
     */
    public function setCharset($charset)
    {
        $this->charset = $charset;

        return $this;
    }

    /**
     * @return string
     */
    public function getTablePrefix()
    {
        return $this->tablePrefix;
    }

    /**
     * @param string $prefix
     *
     * @return MagentoConfiguration
     */
    public function setTablePrefix($prefix)
    {
        $this->tablePrefix = $prefix;

        return $this;
    }

    /**
     * Prepare dbal parameters
     *
     * @return \ArrayAccess
     */
    public function getDbalParameters()
    {
        $params = array(
            'dbname'   => $this->getDbname(),
            'user'     => $this->getUser(),
            'password' => $this->getPassword(),
            'host'     => $this->getHost(),
            'port'     => $this->getPort(),
            'driver'   => $this->getDriver(),
        );

        return $params;
    }
}
