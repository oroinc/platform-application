<?php

namespace Acme\Bundle\DemoDataFlowBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Acme\Bundle\DemoDataFlowBundle\Connector\MagentoConnector;
use Acme\Bundle\DemoDataFlowBundle\Job\ImportAttributes;
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
        $manager = $this->container->get('product_manager');
        $configuration = array(
            'dbal' => array(
                    'driver'   => 'pdo_mysql',
                    'host'     => '127.0.0.1',
                    'dbname'   => 'magento_ab',
                    'user'     => 'root',
                    'password' => 'root',
            ),
            'prefix' => 'ab_'
        );
        $connector = new MagentoConnector($manager, $configuration);
        $connector->process('import_attribute');

        return array();
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
        ->addWriter(new \Ddeboer\DataImport\Writer\CallbackWriter(
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
