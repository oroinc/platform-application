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
        // $connector = $this->container->get('connector.magento_catalog');
        // $connector->configure();

        // get job
        $job = $this->container->get('job.import_attributes');

        // load configuration
        $resource = '@AcmeDemoDataFlowBundle/Resources/files/import_attributes.yml';
        $file = $this->container->get('kernel')->locateResource($resource);
        $parameters = Yaml::parse($file);

        // configure job
        $configuration = new ImportAttributesConfiguration($parameters);
        $job->configure($configuration);

        // run job
        $messages = $job->run();

        // display result message
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
        // $connector = $this->container->get('connector.csv');
        // $connector->configure();

        // get job
        $job = $this->container->get('job.import_customers');

        // load configuration
        $resource = '@AcmeDemoDataFlowBundle/Resources/files/import_customers.yml';
        $csvPath = $this->container->get('kernel')->locateResource('@AcmeDemoDataFlowBundle/Resources/files/export_customers.csv');
        $file = $this->container->get('kernel')->locateResource($resource);
        $parameters = Yaml::parse($file);
        $parameters['parameters']['file_path'] = $csvPath;

        // configure job
        $configuration = new ImportCustomersConfiguration($parameters);
        $job->configure($configuration);

        // run job
        $customers = $job->run();

        $this->get('session')->getFlashBag()->add('success', count($customers) .' has been transformed !');

        foreach ($customers as $customer) {
            $this->get('session')->getFlashBag()->add('info', 'Email -> '. $customer->getEmail());
            $this->get('session')->getFlashBag()->add('info', 'Firstname -> '. $customer->getFirstname());
            $this->get('session')->getFlashBag()->add('info', 'Lastname -> '. $customer->getLastname());

            $this->get('session')->getFlashBag()->add('info', 'Get company mapped from password hash -> '. $customer->getValue('company')->getData());
            $this->get('session')->getFlashBag()->add('info', 'Website -> '. $customer->getValue('website')->getData());
        }

        return $this->redirect($this->generateUrl('acme_demoflexibleentity_customer_index'));
    }

}
