<?php
namespace Acme\Bundle\DemoFormBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Oro\Bundle\NavigationBundle\Annotation\TitleTemplate;

use Acme\Bundle\DemoFormBundle\Form\Model\Person;
use Acme\Bundle\DemoFormBundle\Form\Type\PersonType;

/**
 * @Route("/person")
 */
class PersonController extends Controller
{
    /**
     * @Route("/create", name="acme_demo_form_person_create")
     * @TitleTemplate("Form submit")
     * @Template()
     */
    public function createAction(Request $request)
    {
        $form = $this->createForm(
            new PersonType(),
            new Person()
        );
        return array('form' => $form->createView());
    }

    /**
     * @Route("/create/errors")
     * @TitleTemplate("Form submit")
     * @Template("AcmeDemoFormBundle:Person:create.html.twig")
     */
    public function createErrorsAction()
    {
        $person = new Person();
        $form = $this->createForm(
            new PersonType(),
            $person
        );
        $form->bind(
            array(
                'username' => 'john.doe',
                'email' => 'email',
                'plainPassword' => array(
                    'first' => 'password',
                    'second' => 'pa$$word',
                ),
                'dob' => '123456',
                'avatar' => 'not a file',
                'acceptTerms' => 0
            )
        );
        $form->isValid();
        return array('form' => $form->createView());
    }
}
