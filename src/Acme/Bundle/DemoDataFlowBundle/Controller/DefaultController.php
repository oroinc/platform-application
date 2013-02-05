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
     * @Route("/test")
     * @Template("AcmeDemoDataFlowBundle:Default:index.html.twig")
     *
     * @return array
     */
    public function testAction()
    {
        // get connector
        $connector = $this->container->get('connector.magento_catalog');

        // get import attributes job
        $job = $this->container->get('job.import_attributes');

        // add job to connector and execute it
        /*
        $connector->addJob($job);
        $connector->process($job->getCode());
        */

        $messages = $job->process();
        foreach ($messages as $message) {
            $this->get('session')->getFlashBag()->add($message[0], $message[1]);
        }

        return $this->redirect($this->generateUrl('acme_demoflexibleentity_productattribute_index'));
    }

    /**
     * @Route("/list-connectors")
     * @Template()
     *
     * @return array
     */
    public function listConnectorsAction()
    {
        $connectors = $this->container->get('oro_dataflow.connectors');

        return array('connectors' => $connectors->getConnectors());
    }

    /**
     * @Route("/index")
     * @Template()
     *
     * @return array
     */
    public function indexAction()
    {

        // Create the source; here we use an HTTP one
        /*
        $source = new Http('http://www.opta.nl/download/registers/nummers_csv.zip');

        // As the source file is zipped, we add an unzip filter
        $source->addFilter(new Unzip('nummers.csv'));

        // Retrieve the \SplFileObject from the source
        $file = $source->getFile();
        */

        // Create and configure the reader
        $file = new \SplFileObject('/tmp/truc.csv');
        $csvReader = new CsvReader($file);
        $csvReader->setHeaderRowNumber(0);

        // Create the workflow
        $workflow = new Workflow($csvReader);
        $dateTimeConverter = new DateTimeValueConverter();

        // Add converters to the workflow
        $workflow
        ->addValueConverter('twn_datumbeschikking', $dateTimeConverter)
        ->addValueConverter('twn_datumeind', $dateTimeConverter)
        ->addValueConverter('datummutatie', $dateTimeConverter)

        // You can also add closures as converters
        ->addValueConverter('twn_nummertm',
            new \Ddeboer\DataImport\ValueConverter\CallbackValueConverter(
                function($input) {
                    return str_replace('-', '', $input);
                }
            )
        )
        ->addValueConverter('twn_nummervan',
            new \Ddeboer\DataImport\ValueConverter\CallbackValueConverter(
                function($input) {
                    return str_replace('-', '', $input);
                }
            )
        )

        // Use one of the writers supplied with this bundle, implement your own, or use
        // a closure:
        ->addWriter(
            new \Ddeboer\DataImport\Writer\CallbackWriter(
                function($csvLine) {
                    var_dump($csvLine);
                }
            )
        );

        // Process the workflow
        $workflow->process();

        return array('name' => $name);
    }
}
