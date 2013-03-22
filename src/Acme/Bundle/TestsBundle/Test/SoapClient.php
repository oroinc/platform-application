<?php
namespace Acme\Bundle\TestsBundle\Test;

use \SoapClient as BasicSoapClient;

class SoapClient extends BasicSoapClient
{
    public $kernel;

    /**
     * Overridden constructor
     *
     * @param string $wsdl
     * @param array $options
     * @param \Symfony\Bundle\FrameworkBundle\Client $client
     */
    public function __construct($wsdl, $options, &$client)
    {
        $this->kernel =  $client;
        parent::__construct($wsdl, $options);

    }

    /**
     * Overridden _doRequest method
     *
     * @param string $request
     * @param string $location
     * @param string $action
     * @param int $version
     * @param int $one_way
     *
     * @return string
     */
    public function __doRequest($request, $location, $action, $version, $one_way = 0)
    {
        ob_start();
        //save directly in _SERVER array
        $_SERVER['HTTP_SOAPACTION'] = $action;
        $_SERVER['CONTENT_TYPE'] = 'application/soap+xml';
        //make POST request
        $this->kernel->request('POST', $location, array(), array(), array(), $request);
        ob_end_clean();
        unset($_SERVER['HTTP_SOAPACTION']);
        unset($_SERVER['CONTENT_TYPE']);
        return $this->kernel->getResponse()->getContent();
    }
}
