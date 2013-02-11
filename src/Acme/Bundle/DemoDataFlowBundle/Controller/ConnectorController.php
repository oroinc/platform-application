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
        // get connectors ids
        $connectors = $this->container->get('oro_dataflow.connectors')->getConnectors();
        $connectorIds = array_keys($connectors);

        return array('connectorIds' => $connectorIds);
    }

    /**
     * @Route("/configure/{connectorId}/{configurationId}", defaults={"configurationId"=0})
     * @Template()
     *
     * @return array
     */
    public function configureAction($connectorId, $configurationId)
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

        /*
        // prepare form submission
        if ('POST' === $this->request->getMethod()) {
            if ($this->get($connector->getFormHandlerId())->process($configuration)) {
                $this->get('session')->getFlashBag()->add('success', 'Configuration successfully saved');

                return $this->redirect($this->generateUrl('acme_demodataflow_connector_edit'));
            }
        }
        */

        // render configuration form
        return array(
            'form'           => $form->createView(),
            'configurations' => $configurations,
            'connectorId'    => $connectorId
        );
    }


    /**
     * @Route("/edit/{configuration}", requirements={"configuration"="\d+"}, defaults={"configuration"=0})
     * @Template()
     *
     * @return array
     */
    public function editAction($configuration/*, $connectorId = null*/)
    {



        /*if ($connectorId) {
            $connector = $this->container->get($connectorId);
        } else {*/
            $connector = $this->container->get('connector.magento_catalog');
        //}

            if ($configuration == 0) {


                $configuration = $connector->getNewConfigurationInstance();
            }

        if ($this->get($connector->getFormHandlerId())->process($configuration)) {
            $this->get('session')->getFlashBag()->add('success', 'Configuration successfully saved');

            return $this->redirect($this->generateUrl('acme_demodataflow_connector_edit'));
        }

        return array(
            'form' => $this->get($connector->getFormId())->createView(),
        );
    }

    /**
     * @Route("/configure-job")
     * @Template()
     *
     * @return array
     */
    public function configureJobAction()
    {
        // TODO : get existing stored conf by conftype
        // select conf or create a new one and save

        // $configuration = jobservice-> getConfiguration()
        // jobservice-> getFormType($configuration)
        //

        return array();
    }

    /**
     * @Route("/run-job")
     * @Template()
     *
     * @return array
     */
    public function runJobAction()
    {
        // $configuration = jobservice-> getConfiguration()
        // jobservice-> getFormType($configuration)
        //

        return array();
    }

}
