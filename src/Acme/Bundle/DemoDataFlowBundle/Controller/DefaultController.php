<?php

namespace Acme\Bundle\DemoDataFlowBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Yaml\Yaml;

use Acme\Bundle\DemoDataFlowBundle\Connector\MagentoConnector;
use Acme\Bundle\DemoDataFlowBundle\Connector\ImportAttributes;
use Acme\Bundle\DemoDataFlowBundle\Configuration\ImportAttributesConfiguration;
use Acme\Bundle\DemoDataFlowBundle\Configuration\ImportCustomersConfiguration;

/**
 * Demo dataflow controller
 *
 * @author    Nicolas Dupont <nicolas@akeneo.com>
 * @copyright 2012 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/MIT MIT
 *
 * @Route("/default")
 */
class DefaultController extends Controller
{

    /**
     * @Route("/connectors")
     * @Template()
     *
     * @return array
     */
    public function connectorsAction()
    {
        $connectors = $this->container->get('oro_dataflow.connectors');

        return array(
            'connectors'       => $connectors->getConnectors(),
            'jobs'             => $connectors->getJobs(),
            'connectorsToJobs' => $connectors->getConnectorToJobs()
        );
    }

    /**
     * @Route("/import-attributes")
     * @Template("AcmeDemoDataFlowBundle:Default:index.html.twig")
     *
     * @return array
     */
    public function importAttributesAction()
    {
        // get connector
        $connector = $this->container->get('connector.magento_catalog');

        // get connector configuration TODO: refactor serialization
        $configRepo = $this->get('doctrine.orm.entity_manager')->getRepository('OroDataFlowBundle:Configuration');
        $typeName = $connector->getConfigurationName();
        $confData =  $configRepo->findOneByTypeAndDescription($typeName, 'Magento 2');
        $serializer = \JMS\Serializer\SerializerBuilder::create()->build();
        $configuration = $serializer->deserialize($confData->getData(), $confData->getTypeName(), $confData->getFormat());

        // configure connector
        $connector->configure($configuration);

        // get job
        $job = $this->container->get('job.import_attributes');

        // get job configuration TODO: refactor serialization
        $configRepo = $this->get('doctrine.orm.entity_manager')->getRepository('OroDataFlowBundle:Configuration');
        $typeName = $job->getConfigurationName();
        $confData =  $configRepo->findOneByTypeAndDescription($typeName, 'Import attributes');
        $serializer = \JMS\Serializer\SerializerBuilder::create()->build();
        $configuration = $serializer->deserialize($confData->getData(), $confData->getTypeName(), $confData->getFormat());

        // configure job
        $job->configure($connector->getConfiguration(), $configuration);

        // run job
        $job->run();
        $messages = $job->getMessages();

        // display result messages
        foreach ($messages as $message) {
            $this->get('session')->getFlashBag()->add($message[0], $message[1]);
        }

        return $this->redirect($this->generateUrl('acme_demoflexibleentity_productattribute_index'));
    }

    /**
     * Call customer import
     *
     * @Route("/import-customer")
     * @Template()
     *
     * @return array
     */
    public function importCustomersAction()
    {
        // get connector
        $connector = $this->container->get('connector.csv');

        // get connector configuration TODO: refactor serialization
        $configRepo = $this->get('doctrine.orm.entity_manager')->getRepository('OroDataFlowBundle:Configuration');
        $typeName = $connector->getConfigurationName();
        $confData =  $configRepo->findOneByTypeAndDescription($typeName, 'Magento CSV');
        $serializer = \JMS\Serializer\SerializerBuilder::create()->build();
        $configuration = $serializer->deserialize($confData->getData(), $confData->getTypeName(), $confData->getFormat());

        // configure connector
        $connector->configure($configuration);

        // get job
        $job = $this->container->get('job.import_customers');

        // get job configuration TODO: refactor serialization
        $configRepo = $this->get('doctrine.orm.entity_manager')->getRepository('OroDataFlowBundle:Configuration');
        $typeName = $job->getConfigurationName();
        $confData =  $configRepo->findOneByTypeAndDescription($typeName, 'Import customer CSV');
        $serializer = \JMS\Serializer\SerializerBuilder::create()->build();
        $configuration = $serializer->deserialize($confData->getData(), $confData->getTypeName(), $confData->getFormat());

        // configure job
        $job->configure($connector->getConfiguration(), $configuration);

        // run job
        $job->run();
        $messages = $job->getMessages();

        // display result messages
        foreach ($messages as $message) {
            $this->get('session')->getFlashBag()->add($message[0], $message[1]);
        }

        return $this->redirect($this->generateUrl('acme_demoflexibleentity_customer_index'));
    }
}
