<?php
namespace Acme\Bundle\DemoDataFlowBundle\Form\Handler;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

use Doctrine\Common\Persistence\ObjectManager;

use Acme\Bundle\DemoDataFlowBundle\Configuration\NewMagentoConfiguration;

/**
 * Configuration form handler
 *
 * @author    Nicolas Dupont <nicolas@akeneo.com>
 * @copyright 2012 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/MIT MIT
 *
 */
class MagentoConnectorHandler
{
    /**
     * @var FormInterface
     */
    protected $form;

    /**
     * @var Request
     */
    protected $request;

    /**
     * @var ObjectManager
     */
    protected $manager;

    /**
     *
     * @param FormInterface $form
     * @param Request $request
     * @param ObjectManager $manager
     */
    public function __construct(FormInterface $form, Request $request, ObjectManager $manager)
    {
        $this->form    = $form;
        $this->request = $request;
        $this->manager = $manager;
    }

    /**
     * Process form
     *
     * @param  NewMagentoConfiguration $entity
     * @return bool True on successfull processing, false otherwise
     */
    public function process(NewMagentoConfiguration $entity)
    {
        $this->form->setData($entity);

        if ('POST' === $this->request->getMethod()) {
            $this->form->bind($this->request);

            if ($this->form->isValid()) {
                $this->onSuccess($entity);

                return true;
            }
        }

        return false;
    }

    /**
     * "Success" form handler
     *
     * @param NewMagentoConfiguration $entity
     */
    protected function onSuccess(NewMagentoConfiguration $entity)
    {
        // TODO convert to json save in file ?
/*

        public function serialize($format)
        {
            $encoders = array(new XmlEncoder(), new JsonEncoder());
            $normalizers = array(new GetSetMethodNormalizer());
            $serializer = new Serializer($normalizers, $encoders);

            return $serializer->serialize($this, $format);
        }*/

        var_dump($entity->serialize('xml'));
        exit();

        /*
        $this->manager->persist($entity);
        $this->manager->flush();*/
    }
}