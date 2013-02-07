<?php

namespace Acme\Bundle\DemoDataFlowBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Acme\Bundle\DemoDataFlowBundle\Connector\MagentoConnector;
use Acme\Bundle\DemoDataFlowBundle\Connector\ImportAttributes;
use Ddeboer\DataImport\Workflow;
use Ddeboer\DataImport\Source\Http;
use Ddeboer\DataImport\Source\Filter\Unzip;
use Ddeboer\DataImport\Reader\CsvReader;
use Ddeboer\DataImport\ValueConverter\DateTimeValueConverter;

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

        return array('connectors' => $connectors->getConnectors());
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
        //$connector->configure();

        // get import attributes job
        //$job = $connector->getJob('import_attributes');
        $job = $this->container->get('job.import_attributes');

        // configure job
        $parameters = array(
            'params' => array(
                'host'         => '127.0.0.1',
                'dbname'       => 'magento_ab',
                'user'         => 'root',
                'password'     => 'root',
                'table_prefix' => 'ab_'
            )
        );
        $job->configure($parameters);

        // run
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
        //$connector = $this->container->get('connector.magento_customer');

        // get job
        //$job = $connector->getJob('import_customers');

        // TODO !!!
        $job = $this->container->get('job.import_customers');

        // configure job
        $csvPath = $this->container->get('kernel')->locateResource('@AcmeDemoDataFlowBundle/Resources/files/export_customers.csv');
        $parameters = array('params' => array('csv_path' => $csvPath));
        $job->configure($parameters);

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
