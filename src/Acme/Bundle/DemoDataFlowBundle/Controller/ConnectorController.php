<?php
namespace Acme\Bundle\DemoDataFlowBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Symfony\Component\Form\FormInterface;
use Oro\Bundle\DataFlowBundle\Configuration\ConfigurationInterface;
use Oro\Bundle\DataFlowBundle\Configuration\EditableConfigurationInterface;
use Oro\Bundle\DataFlowBundle\Form\Handler\ConfigurationHandler;

/**
 * Connector controller
 *
 * @author    Nicolas Dupont <nicolas@akeneo.com>
 * @copyright 2012 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/MIT MIT
 *
 * @Route("/connector")
 *
 */
class ConnectorController extends Controller
{

    /**
     * Select a job
     *
     * @Route("/select")
     * @Template()
     *
     * @return array
     */
    public function selectAction()
    {
        // get connectors ids to job ids
        $connectorsToJobs = $this->container->get('oro_dataflow.connectors')->getConnectorToJobs();

        return array('connectorsToJobs' => $connectorsToJobs);
    }

    /**
     * Get configuration form
     *
     * @param EditableConfigurationInterface $configurable  the configurable service
     * @param ConfigurationInterface         $configuration the configuration to set if provided
     *
     * @return FormInterface
     */
    protected function getConfigurationForm(EditableConfigurationInterface $configurable, ConfigurationInterface $configuration = null)
    {
        $formService = $this->get($configurable->getConfigurationFormServiceId());
        if ($configuration) {
            $formService->setData($configuration);
        }

        return $formService;
    }

    /**
     * Get configuration form handler
     *
     * @param EditableConfigurationInterface $configurable the configurable service
     *
     * @return ConfigurationHandler
     */
    protected function getConfigurationHandler(EditableConfigurationInterface $configurable)
    {
        $handler = $this->get($configurable->getConfigurationFormHandlerServiceId());
        $form = $this->getConfigurationForm($configurable);
        $handler->setForm($form);

        return $handler;
    }

    /**
     * Get configuration
     *
     * @param EditableConfigurationInterface $configurable    the configurable service
     * @param integer                        $configurationId the conf id
     *
     * @return ConfigurationInterface the configuration
     */
    protected function getConfiguration(EditableConfigurationInterface $configurable, $configurationId)
    {
        $configRepo = $this->get('doctrine.orm.entity_manager')->getRepository('OroDataFlowBundle:Configuration');
        if ($configurationId > 0) {
            $confData      = $configRepo->find($configurationId);
            $serializer    = \JMS\Serializer\SerializerBuilder::create()->build();
            $configuration = $serializer->deserialize($confData->getData(), $confData->getTypeName(), $confData->getFormat());
        } else {
            $configuration = $configurable->getNewConfigurationInstance();
        }

        return $configuration;
    }

    /**
     * Get available configurations
     *
     * @param string $configurationType the configuration FQCN
     *
     * @return \ArrayAccess the configurations
     */
    protected function getAvailableConfigurations($configurationType)
    {
        $configRepo = $this->get('doctrine.orm.entity_manager')->getRepository('OroDataFlowBundle:Configuration');

        return $configRepo->findBy(array('typeName' => $configurationType));
    }

    /**
     * Configure connector
     *
     * @param integer $connectorId     the connector id
     * @param integer $jobId           the job id
     * @param integer $configurationId the configuration id
     *
     * @Route("/configure/{connectorId}/{jobId}/{configurationId}", defaults={"configurationId"=0})
     * @Template()
     *
     * @return array
     */
    public function configureAction($connectorId, $jobId, $configurationId)
    {
        // get connector
        $connector = $this->container->get($connectorId);

        // get existing configuration or create a new one
        $configuration = $this->getConfiguration($connector, $configurationId);
        $configurations = $this->getAvailableConfigurations(get_class($configuration));

        // prepare form
        $form = $this->getConfigurationForm($connector, $configuration);

        // render configuration form
        return array(
            'form'           => $form->createView(),
            'configurations' => $configurations,
            'connectorId'    => $connectorId,
            'jobId'          => $jobId,
        );
    }

    /**
     * Save connector
     *
     * @param integer $connectorId     the connector id
     * @param integer $jobId           the job id
     * @param integer $configurationId the configuration id
     *
     * @Route("/save/{connectorId}/{jobId}/{configurationId}", defaults={"configurationId"=0})
     * @Template()
     *
     * @return array
     */
    public function saveAction($connectorId, $jobId, $configurationId)
    {
        // TODO refactor !

        // get connector
        $connector = $this->container->get($connectorId);

        // get existing configuration or create a new one
        $configuration = $this->getConfiguration($connector, $configurationId);

        // process form
        $handler = $this->getConfigurationHandler($connector);
        if ($handler->process($configuration)) {
            $this->get('session')->getFlashBag()->add('success', 'Configuration successfully saved');

            return $this->redirect($this->generateUrl('acme_demodataflow_connector_configurejob', array('connectorId' => $connectorId, 'jobId' => $jobId)));
        } else {
            $this->get('session')->getFlashBag()->add('error', "Configuration can't be saved");
        }

        return $this->redirect($this->generateUrl('acme_demodataflow_connector_configure', array('connectorId' => $connectorId, 'jobId' => $jobId, 'configurationId' => $configurationId)));
    }

    /**
     * Configure job
     *
     * @param integer $connectorId     the connector id
     * @param integer $jobId           the job id
     * @param integer $configurationId the configuration id
     *
     * @Route("/configurejob/{connectorId}/{jobId}/{configurationId}", defaults={"configurationId"=0})
     * @Template()
     *
     * @return array
     */
    public function configureJobAction($connectorId, $jobId, $configurationId)
    {
        // get job
        $job = $this->container->get($jobId);

        // get existing configuration or create a new one
        $configuration = $this->getConfiguration($job, $configurationId);
        $configurations = $this->getAvailableConfigurations(get_class($configuration));

        // prepare form
        $form = $this->getConfigurationForm($job, $configuration);

        // render configuration form
        return array(
            'form'           => $form->createView(),
            'configurations' => $configurations,
            'connectorId'    => $connectorId,
            'jobId'          => $jobId,
        );
    }

    /**
     * Save job
     *
     * @param integer $connectorId     the connector id
     * @param integer $jobId           the job id
     * @param integer $configurationId the configuration id
     *
     * @Route("/savejob/{connectorId}/{jobId}/{configurationId}", defaults={"configurationId"=0})
     * @Template()
     *
     * @return array
     */
    public function saveJobAction($connectorId, $jobId, $configurationId)
    {
        // get job
        $job = $this->container->get($jobId);

        // get existing configuration or create a new one
        $configuration = $this->getConfiguration($job, $configurationId);
        $configurations = $this->getAvailableConfigurations(get_class($configuration));

        // process form
        $handler = $this->getConfigurationHandler($job);
        if ($handler->process($configuration)) {
            $this->get('session')->getFlashBag()->add('success', 'Configuration successfully saved');

            return $this->redirect($this->generateUrl('acme_demodataflow_connector_runjob', array('connectorId' => $connectorId, 'jobId' => $jobId)));
        } else {
            $this->get('session')->getFlashBag()->add('error', "Configuration can't be saved");
        }

        // TODO : build form here to keep errors and setted data

        return $this->redirect($this->generateUrl('acme_demodataflow_connector_configurejob', array('connectorId' => $connectorId, 'jobId' => $jobId, 'configurationId' => $configurationId)));
    }

    /**
     * Run job
     *
     * @param integer $connectorId     the connector id
     * @param integer $jobId           the job id
     * @param integer $configurationId the configuration id
     *
     * @Route("/runjob/{connectorId}/{jobId}/{configurationId}", defaults={"configurationId"=0})
     * @Template()
     *
     * @return array
     */
    public function runJobAction($connectorId, $jobId, $configurationId)
    {
        return array(
            'connectorId'    => $connectorId,
            'jobId'          => $jobId,
        );
    }
}
