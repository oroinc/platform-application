<?php
namespace Acme\Bundle\DemoBundle\Controller\Api;

use BeSimple\SoapBundle\ServiceDefinition\Annotation as Soap;
use Symfony\Component\DependencyInjection\ContainerAware;

class SoapController extends ContainerAware
{
    /**
     * @Soap\Method("test")
     * @Soap\Result(phpType = "string")
     */
    public function testAction()
    {
        return $this->container->get('besimple.soap.response')->setReturnValue(
            'test'
        );
    }

    /**
     * @Soap\Method("test1")
     * @Soap\Result(phpType = "string")
     */
    public function test1Action()
    {
        return $this->container->get('besimple.soap.response')->setReturnValue(
            'test1'
        );
    }
}
