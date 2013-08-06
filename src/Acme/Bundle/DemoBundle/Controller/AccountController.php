<?php

namespace Acme\Bundle\DemoBundle\Controller;

use FOS\Rest\Util\Codes;
use Oro\Bundle\EntityConfigBundle\Provider\ConfigProvider;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Acme\Bundle\DemoBundle\Entity\Account;
use Acme\Bundle\DemoBundle\Form\AccountType;
use Symfony\Component\HttpFoundation\Response;

/**
 * Account controller.
 *
 * @Route("/account")
 */
class AccountController extends Controller
{
    /**
     * Lists all Account entities.
     *
     * @Route("/", name="account")
     * @Template()
     */
    public function indexAction(Request $request)
    {
        $datagrid = $this->get('account.datagrid_manager')->getDatagrid();
        $view     = 'json' == $request->getRequestFormat()
            ? 'OroGridBundle:Datagrid:list.json.php'
            : 'AcmeDemoBundle:Account:index.html.twig';

        return $this->render(
            $view,
            array('datagrid' => $datagrid->createView())
        );

    }

    /**
     * Create Account
     *
     * @Route("/create", name="account_create")
     * @Template("AcmeDemoBundle:Account:update.html.twig")
     */
    public function createAction()
    {
        return $this->updateAction(new Account());
    }

    /**
     * Edit Account
     *
     * @Route("/update/{id}", name="account_update", requirements={"id"="\d+"}, defaults={"id"=0})
     * @Template("AcmeDemoBundle:Account:update.html.twig")
     */
    public function updateAction(Account $entity = null)
    {
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Account entity.');
        }

        $request = $this->getRequest();
        $updateForm = $this->createForm(new AccountType(), $entity);

        if ($request->getMethod() == 'POST') {
            $updateForm->bind($request);

            if ($updateForm->isValid()) {
                $this->getDoctrine()->getManager()->persist($entity);
                $this->getDoctrine()->getManager()->flush($entity);

                return $this->redirect($this->generateUrl('account'));
            }
        }

        $audit_enabled = false;

        /** @var ConfigProvider $entityAuditProvider */
        $entityAuditProvider = $this->get('oro_dataaudit.config.config_provider');
        if ($entityAuditProvider->hasConfig(get_class($entity))) {
            $audit_enabled = $entityAuditProvider->getConfig(get_class($entity))->is('auditable');
        }

        return array(
            'form'          => $updateForm->createView(),
            'audit_enabled' => $audit_enabled
        );
    }

    /**
     * Deletes Account
     *
     * @Route("/delete/{id}", name="account_delete")
     */
    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('AcmeDemoBundle:Account')->find($id);

        if (!$entity) {
            return new Response('', Codes::HTTP_FORBIDDEN);
        }

        $em->remove($entity);
        $em->flush();

        $this->get('session')->getFlashBag()->add('success', 'Item was deleted');

        return new Response('', Codes::HTTP_NO_CONTENT);
    }
}
