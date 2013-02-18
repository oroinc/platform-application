<?php
namespace Acme\Bundle\DemoDataFlowBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Symfony\Component\Form\FormInterface;
use Oro\Bundle\DataFlowBundle\Configuration\ConfigurationInterface;
use Oro\Bundle\DataFlowBundle\Configuration\EditableConfigurationInterface;
use Oro\Bundle\DataFlowBundle\Form\Handler\ConfigurationHandler;
use Oro\Bundle\DataFlowBundle\Entity\Connector;

/**
 * Configure controller
 *
 * @author    Nicolas Dupont <nicolas@akeneo.com>
 * @copyright 2012 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/MIT MIT
 *
 * @Route("/configure")
 *
 */
class ConfigureController extends Controller
{

    /**
     * Build contextual navigation menu wit steps
     *
     * @return \ArrayAccess
     */
    public function getNavigationMenu()
    {
        // get params
        $request     = $this->container->get('request');
        $connectorId = $request->get('id');

        // prepare steps
        $translator = $this->container->get('translator');
        $items = array(
            // select
            array(
                'label'  => $translator->trans('(1) Select connector'),
                'route'  => 'acme_demodataflow_configure_select',
                'params' => array()
            ),
            // configure connector
            array(
                'label' => $translator->trans('(2) Configure connector'),
                'route' => 'acme_demodataflow_configure_edit',
                'params' => array('id' => $connectorId)
            ),
            // configure job
            array(
                'label'  => $translator->trans('(3) Configure import / export'),
                'route'  => 'acme_demodataflow_configure_configure',
                'params' => array('id' => $connectorId)
            ),
            // schedule / run
            array(
                'label'  => $translator->trans('(4) Run'),
                'route'  => 'acme_demodataflow_configure_run',
                'params' => array('id' => $connectorId)
            )
        );

        // highlight current step, disable following
        $currentRoute = $request->get('_route');
        $toDisable    = false;
        foreach ($items as &$item) {
            if ($item['route'] == $currentRoute) {
                $item['class']= 'active';
                $toDisable = true;
            } elseif ($toDisable) {
                $item['route']= false;
            }
        }

        return $items;
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
            $configuration = $serializer->deserialize(
                $confData->getData(), $confData->getTypeName(), $confData->getFormat()
            );
            $configuration->setId($confData->getId());
            $configuration->setDescription($confData->getDescription());
        } else {
            $configurationClass = $configurable->getConfigurationName();
            $configuration = new $configurationClass();
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

        $connectors = $this->get('doctrine.orm.entity_manager')->getRepository('OroDataFlowBundle:Connector')->findAll();

        $jobs = $this->get('doctrine.orm.entity_manager')->getRepository('OroDataFlowBundle:Job')->findAll();

        return array(
            'connectors'       => $connectors,
            'jobs'             => $jobs,
            'connectorsToJobs' => $connectorsToJobs,
            'steps'            => $this->getNavigationMenu()
        );
    }

    /**
     * Create connector
     *
     * @Route("/create/{serviceId}")
     * @Template("AcmeDemoDataFlowBundle:Configure:edit.html.twig")
     *
     * @return null
     */
    public function createAction($serviceId)
    {
        $connector = new Connector();
        $connector->setServiceId($serviceId);

        return $this->editAction($connector);
    }

    /**
     * Edit Connector
     *
     * @Route("/edit/{id}", requirements={"id"="\d+"}, defaults={"id"=0})
     * @Template
     */
    public function editAction(Connector $entity)
    {
        // get connector
        $connectorService = $this->container->get($entity->getServiceId());
        $confId = ($entity->getConfiguration()) ? $entity->getConfiguration()->getId() : 0;
        $configuration = $this->getConfiguration($connectorService, $confId);

        // prepare form
        $form = $this->getConfigurationForm($connectorService, $configuration);

        // process form
        if ('POST' === $this->get('request')->getMethod()) {
            $handler = $this->getConfigurationHandler($connectorService);
            $handler->setForm($form);
            if ($handler->process($configuration)) {

                die('redirect');

                $this->get('session')->getFlashBag()->add('success', 'Configuration successfully saved');
                $url = $this->generateUrl('acme_demodataflow_configure_configure', array('id' => $entity->getId()));

                return $this->redirect($url);
            }
        }

        // render configuration form
        return array(
            'form'      => $form->createView(),
            'connector' => $entity,
            'steps'     => $this->getNavigationMenu()
        );
    }

    /**
     * Configure connector
     *
     * @Route("/configure/{id}", requirements={"id"="\d+"}, defaults={"id"=0})
     * @Template("AcmeDemoDataFlowBundle:Configure:edit.html.twig")
     */
    public function configureAction(Connector $entity)
    {
        // get connector
        $connectorService = $this->container->get($entity->getServiceId());
        $confId = ($entity->getConfiguration()) ? $entity->getConfiguration()->getId() : 0;
        $configuration = $this->getConfiguration($connectorService, $confId);

        // prepare form
        $form = $this->getConfigurationForm($connectorService, $configuration);

        // process form
        if ('POST' === $this->get('request')->getMethod()) {
            $handler = $this->getConfigurationHandler($connectorService);
            $handler->setForm($form);
            if ($handler->process($configuration)) {
                $this->get('session')->getFlashBag()->add('success', 'Configuration successfully saved');
                $url = $this->generateUrl('acme_demodataflow_configure_job', array('id' => $entity->getId()));

                return $this->redirect($url);
            }
        }

        // render configuration form
        return array(
            'form'      => $form->createView(),
            'connector' => $entity,
            'steps'     => $this->getNavigationMenu()
        );
    }





    /**
     * Configure connector
     *
     * @param integer $conId     the connector id
     * @param integer $jobId     the job id
     * @param integer $conConfId the connector configuration id
     *
     * @Route("/connector/{conId}/{jobId}/{conConfId}", defaults={"conConfId"=0})
     * @Template()
     *
     * @return array
     */
    public function connectorAction($conId, $jobId, $conConfId)
    {
        // get connector
        $connector = $this->container->get($conId);

        // get existing configuration or create a new one
        $configuration = $this->getConfiguration($connector, $conConfId);
        $configurations = $this->getAvailableConfigurations(get_class($configuration));

        // prepare form
        $form = $this->getConfigurationForm($connector, $configuration);

        // process form
        if ('POST' === $this->get('request')->getMethod()) {
            $handler = $this->getConfigurationHandler($connector);
            $handler->setForm($form);
            if ($handler->process($configuration)) {
                $this->get('session')->getFlashBag()->add('success', 'Configuration successfully saved');
                $params = array('conId' => $conId, 'jobId' => $jobId, 'conConfId' => $configuration->getId());

                return $this->redirect($this->generateUrl('acme_demodataflow_configure_job', $params));
            }
        }

        // render configuration form
        return array(
            'form'            => $form->createView(),
            'configurations'  => $configurations,
            'conId'           => $conId,
            'jobId'           => $jobId,
            'conConfId'       => $conConfId,
            'steps'           => $this->getNavigationMenu()
        );
    }

    /**
     * Configure job
     *
     * @param integer $conId     the connector id
     * @param integer $jobId     the job id
     * @param integer $conConfId the connector configuration id
     * @param integer $jobConfId the job configuration id
     *
     * @Route("/job/{conId}/{jobId}/{conConfId}/{jobConfId}", defaults={"jobConfId"=0})
     * @Template()
     *
     * @return array
     */
    public function jobAction($conId, $jobId, $conConfId, $jobConfId)
    {
        // get job
        $job = $this->container->get($jobId);

        // get existing configuration or create a new one
        $configuration = $this->getConfiguration($job, $jobConfId);
        $configurations = $this->getAvailableConfigurations(get_class($configuration));

        // prepare form
        $form = $this->getConfigurationForm($job, $configuration);

        // process form
        if ('POST' === $this->get('request')->getMethod()) {
            $handler = $this->getConfigurationHandler($job);
            if ($handler->process($configuration)) {
                $this->get('session')->getFlashBag()->add('success', 'Configuration successfully saved');
                $params = array('conId' => $conId, 'jobId' => $jobId, 'conConfId' => $conConfId, 'jobConfId' => $configuration->getId());

                return $this->redirect($this->generateUrl('acme_demodataflow_configure_run', $params));
            }
        }

        // render configuration form
        return array(
            'form'           => $form->createView(),
            'configurations' => $configurations,
            'conId'          => $conId,
            'conConfId'      => $conConfId,
            'jobId'          => $jobId,
            'jobConfId'      => $jobConfId,
            'steps'          => $this->getNavigationMenu()
        );
    }

    /**
     * Run
     *
     * @param Connector $connector
     *
     * @Route("/run/{id}", requirements={"id"="\d+"}, defaults={"id"=0})
     * @Template()
     *
     * @return array
     */
    public function runAction(Connector $entity)
    {

        return array(
            'connector' => $entity,
            'steps'     => $this->getNavigationMenu()
        );
    }
}
