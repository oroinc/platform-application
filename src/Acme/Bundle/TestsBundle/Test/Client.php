<?php

namespace Acme\Bundle\TestsBundle\Test;

use Symfony\Bundle\FrameworkBundle\Client as BaseClient;
use Acme\Bundle\TestsBundle\Test\SoapClient;

class Client extends BaseClient
{
    /** @var shared doctine connection */
    public $soapClient;

    static protected $connection;

    protected $hasPerformedRequest;

    /**
     * @param null $wsdl
     * @param array $options
     * @throws \Exception
     */
    public function soap($wsdl = null, array $options = null)
    {
        if (is_null($wsdl)) {
            throw new \Exception('wsdl should not be NULL');
        }

        $this->request('GET', $wsdl);
        $wsdl = $this->getResponse()->getContent();
        //save to file
        $file=tempnam(sys_get_temp_dir(), date("Ymd") . '_') . '.xml';
        $fl = fopen($file, "w");
        fwrite($fl, $wsdl);
        fclose($fl);

        $this->soapClient = new SoapClient($file, $options, $this);

        unlink($file);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function doRequest($request)
    {
        if ($this->hasPerformedRequest) {
            $this->kernel->shutdown();
            $this->kernel->boot();
        } else {
            $this->hasPerformedRequest = true;
        }

        $this->getContainer()->set('doctrine.dbal.default_connection', self::$connection);

        $response = $this->kernel->handle($request);

        if ($this->kernel instanceof TerminableInterface) {
            $this->kernel->terminate($request, $response);
        }
        return $response;
    }

    /**
     * @param $folder
     */
    public function appendFixtures($folder)
    {
        $loader = new \Doctrine\Common\DataFixtures\Loader;
        $fixtures = $loader->loadFromDirectory($folder);
        foreach ($fixtures as $fixture) {
            $fixture->setContainer($this->getContainer());
        }
        $purger = new \Doctrine\Common\DataFixtures\Purger\ORMPurger($this->getContainer()->get('doctrine.orm.entity_manager'));
        $executor = new \Doctrine\Common\DataFixtures\Executor\ORMExecutor(
            $this->getContainer()->get('doctrine.orm.entity_manager'),
            $purger
        );
        $executor->execute($loader->getFixtures(), true);
    }

    public function startTransaction()
    {
        self::$connection = $this->getContainer()->get('doctrine.dbal.default_connection');
        $this->getContainer()->set('doctrine.dbal.default_connection', self::$connection);
        self::$connection->beginTransaction();
    }

    public static function rollbackTransaction()
    {
        self::$connection->rollback();
    }
}
