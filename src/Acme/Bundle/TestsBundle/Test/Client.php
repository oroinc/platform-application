<?php

namespace Acme\Bundle\TestsBundle\Test;

use Symfony\Bundle\FrameworkBundle\Client as BaseClient;
use Acme\Bundle\TestsBundle\Test\SoapClient;

class Client extends BaseClient
{
    public $soapClient;

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
}
