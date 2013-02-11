<?php
namespace Acme\Bundle\DemoDataFlowBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Connector controller
 *
 * @author    Nicolas Dupont <nicolas@akeneo.com>
 * @copyright 2012 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/MIT MIT
 *
 * @Route("/connector")
 */
class ConnectorController extends Controller
{

    /**
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
        $configRepo = $this->get('doctrine.orm.entity_manager')->getRepository('OroDataFlowBundle:Configuration');
        if ($configurationId > 0) {
            $confData = $configRepo->find($configurationId);
            $serializer = \JMS\Serializer\SerializerBuilder::create()->build();
            $configuration = $serializer->deserialize($confData->getData(), $confData->getTypeName(), $confData->getFormat());
        } else {
            $configuration = $connector->getNewConfigurationInstance();
        }
        $configurations = $configRepo->findBy(array('typeName' => get_class($configuration)));

        // prepare form
        $form = $this->get($connector->getFormId());
        $form->setData($configuration);

        // render configuration form
        return array(
            'form'           => $form->createView(),
            'configurations' => $configurations,
            'connectorId'    => $connectorId,
            'jobId'          => $jobId,
        );
    }


    /**
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
        $configRepo = $this->get('doctrine.orm.entity_manager')->getRepository('OroDataFlowBundle:Configuration');
        if ($configurationId > 0) {
            $confData = $configRepo->find($configurationId);
            $serializer = \JMS\Serializer\SerializerBuilder::create()->build();
            $configuration = $serializer->deserialize($confData->getData(), $confData->getTypeName(), $confData->getFormat());
        } else {
            $configuration = $connector->getNewConfigurationInstance();
        }

        // process form
        $handler = $this->get($connector->getFormHandlerId());
        $form = $this->get($connector->getFormId());
        $handler->setForm($form);
        if ($handler->process($configuration)) {
            $this->get('session')->getFlashBag()->add('success', 'Configuration successfully saved');

            return $this->redirect($this->generateUrl('acme_demodataflow_connector_configurejob', array('connectorId' => $connectorId, 'jobId' => $jobId)));
        } else {
            $this->get('session')->getFlashBag()->add('notice', "Configuration can't be saved");
        }

        return $this->redirect($this->generateUrl('acme_demodataflow_connector_configure', array('connectorId' => $connectorId, 'jobId' => $jobId, 'configurationId' => $configurationId)));
    }

    /**
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
        $configRepo = $this->get('doctrine.orm.entity_manager')->getRepository('OroDataFlowBundle:Configuration');
        if ($configurationId > 0) {
            $confData = $configRepo->find($configurationId);
            $serializer = \JMS\Serializer\SerializerBuilder::create()->build();
            $configuration = $serializer->deserialize($confData->getData(), $confData->getTypeName(), $confData->getFormat());
        } else {
            $configuration = $job->getNewConfigurationInstance();
        }
        $configurations = $configRepo->findBy(array('typeName' => get_class($configuration)));

        // prepare form
        $form = $this->get($job->getFormId());
        $form->setData($configuration);

        // render configuration form
        return array(
            'form'           => $form->createView(),
            'configurations' => $configurations,
            'connectorId'    => $connectorId,
            'jobId'          => $jobId,
        );
    }

    /**
     * @Route("/savejob/{connectorId}/{jobId}/{configurationId}", defaults={"configurationId"=0})
     * @Template()
     *
     * @return array
     */
    public function saveJobAction($connectorId, $jobId, $configurationId)
    {
        // TODO refactor !

        // get job
        $job = $this->container->get($jobId);

        // get existing configuration or create a new one
        $configRepo = $this->get('doctrine.orm.entity_manager')->getRepository('OroDataFlowBundle:Configuration');
        if ($configurationId > 0) {
            $confData = $configRepo->find($configurationId);
            $serializer = \JMS\Serializer\SerializerBuilder::create()->build();
            $configuration = $serializer->deserialize($confData->getData(), $confData->getTypeName(), $confData->getFormat());
        } else {
            $configuration = $job->getNewConfigurationInstance();
        }
        $configurations = $configRepo->findBy(array('typeName' => get_class($configuration)));

        // process form
        $handler = $this->get($job->getFormHandlerId());
        $form = $this->get($job->getFormId());
        $handler->setForm($form);
        if ($handler->process($configuration)) {
            $this->get('session')->getFlashBag()->add('success', 'Configuration successfully saved');

            return $this->redirect($this->generateUrl('acme_demodataflow_connector_runjob', array('connectorId' => $connectorId, 'jobId' => $jobId)));
        } else {
            $this->get('session')->getFlashBag()->add('notice', "Configuration can't be saved");
        }

        return $this->redirect($this->generateUrl('acme_demodataflow_connector_configurejob', array('connectorId' => $connectorId, 'jobId' => $jobId, 'configurationId' => $configurationId)));
    }

    /**
     * @Route("/runjob/{connectorId}/{jobId}/{configurationId}", defaults={"configurationId"=0})
     * @Template()
     *
     * @return array
     */
    public function runJobAction($connectorId, $jobId, $configurationId)
    {
        // $configuration = jobservice-> getConfiguration()
        // jobservice-> getFormType($configuration)
        //

        return array(
            'connectorId'    => $connectorId,
            'jobId'          => $jobId,
        );
    }
}
