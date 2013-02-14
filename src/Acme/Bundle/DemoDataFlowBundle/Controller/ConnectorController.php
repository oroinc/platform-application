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
     * Build contextual navigation menu wit steps
     *
     * @return \ArrayAccess
     */
    public function getNavigationMenu()
    {
        // get params
        $request   = $this->container->get('request');
        $conId     = $request->get('conId');
        $jobId     = $request->get('jobId');
        $conConfId = $request->get('conConfId');
        $jobConfId = $request->get('jobConfId');

        // prepare steps
        $translator = $this->container->get('translator');
        $items = array(
            // select
            array(
                'label'  => $translator->trans('(1) Select connector & job'),
                'route'  => 'acme_demodataflow_connector_select',
                'params' => array()
            ),
            // configure connector
            array(
                'label' => $translator->trans('(2) Configure connector'),
                'route' => 'acme_demodataflow_connector_configure',
                'params' => array('conId' => $conId, 'jobId' => $jobId, 'conConfId' => $conConfId)
            ),
            // configure job
            array(
                'label'  => $translator->trans('(3) Configure job'),
                'route'  => 'acme_demodataflow_connector_configurejob',
                'params' => array(
                    'conId' => $conId, 'jobId' => $jobId, 'conConfId' => $conConfId, 'jobConfId' => $jobConfId
                )
            ),
            // schedule / run
            array(
                'label'  => $translator->trans('(4) Schedule / run'),
                'route'  => 'acme_demodataflow_connector_runjob',
                'params' => array(
                    'conId' => $conId, 'jobId' => $jobId, 'conConfId' => $conConfId, 'jobConfId' => $jobConfId
                )
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

        return array(
            'connectorsToJobs' => $connectorsToJobs,
            'steps'            => $this->getNavigationMenu()
        );
    }

    /**
     * Configure connector
     *
     * @param integer $conId     the connector id
     * @param integer $jobId     the job id
     * @param integer $conConfId the connector configuration id
     *
     * @Route("/configure/{conId}/{jobId}/{conConfId}", defaults={"conConfId"=0})
     * @Template()
     *
     * @return array
     */
    public function configureAction($conId, $jobId, $conConfId)
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

                return $this->redirect($this->generateUrl('acme_demodataflow_connector_configurejob', $params));
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
     * @Route("/configurejob/{conId}/{jobId}/{conConfId}/{jobConfId}", defaults={"jobConfId"=0})
     * @Template()
     *
     * @return array
     */
    public function configureJobAction($conId, $jobId, $conConfId, $jobConfId)
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

                return $this->redirect($this->generateUrl('acme_demodataflow_connector_runjob', $params));
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
     * Run job
     *
     * @param integer $conId     the connector id
     * @param integer $jobId     the job id
     * @param integer $conConfId the connector configuration id
     * @param integer $jobConfId the job configuration id
     *
     * @Route("/runjob/{conId}/{jobId}/{conConfId}/{jobConfId}")
     * @Template()
     *
     * @return array
     */
    public function runJobAction($conId, $jobId, $conConfId, $jobConfId)
    {
        // get existing configuration or create a new one
        $repo = $this->get('doctrine.orm.entity_manager')->getRepository('OroDataFlowBundle:Configuration');
        $conConfiguration = $repo->find($conConfId);
        $jobConfiguration = $repo->find($jobConfId);

        return array(
            'conId'     => $conId,
            'conConfId' => $conConfId,
            'jobId'     => $jobId,
            'jobConfId' => $jobConfId,
            'steps'     => $this->getNavigationMenu(),
            'conConf'   => $conConfiguration,
            'jobConf'   => $jobConfiguration,
        );
    }
}
