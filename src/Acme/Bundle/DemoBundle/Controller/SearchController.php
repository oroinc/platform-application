<?php

namespace Acme\Bundle\DemoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use Acme\Bundle\DemoBundle\Entity\Product;
use Acme\Bundle\DemoBundle\Form\ProductType;

use Acme\Bundle\DemoBundle\Entity\Customer;
use Acme\Bundle\DemoBundle\Form\CustomerType;

/**
 * @Route("/search")
 */
class SearchController extends Controller
{
    /**
     * @Route("/product/{id}", name="acme_demo_search_product")
     * @Template()
     */
    public function productPageAction($id)
    {
        return array(
            'product' => $this->getDoctrine()->getRepository('AcmeDemoBundle:Product')->find($id)
        );
    }

    /**
     * @Route("/advanced-search-page", name="acme_demo_advanced_search")
     * @Template()
     * @return array
     */
    public function advancedSearchAction()
    {
        return array();
    }

    /**
     * @Route("/delete", name="acme_demo_delete")
     * @Template()
     * @return array
     */
    public function deleteAction()
    {
        /** @var \Doctrine\ORM\QueryBuilder $qb */
        $qb = $this->getDoctrine()->getManager()->createQueryBuilder();
        $qb->delete('Oro\Bundle\TagBundle\Entity\Tag', 't');

        $qb->andWhere($qb->expr()->eq('t.id', ':id'));
        $qb->setParameter(':id', 2);
        $qb->getQuery()
            ->execute();
        return array();
    }

    /**
     * @Route("/select", name="acme_demo_select")
     * @Template()
     * @return array
     */
    public function selectAction()
    {
        $em = $this->getDoctrine()->getManager();
        /** @var \Doctrine\ORM\QueryBuilder $qb */
        /** @var \Doctrine\ORM\QueryBuilder $subQuery */
        $subQuery = $em->createQueryBuilder();

        //$subQuery->select('user.id')
        //    ->from('Oro\Bundle\UserBundle\Entity\User', 'user');

        $subQuery->select('tag.id')
            ->from('Oro\Bundle\TagBundle\Entity\Tag', 'tag')
            ->leftJoin('Oro\Bundle\TagBundle\Entity\Tag', 'innerTag', 'WITH', 'tag.id = innerTag.id')
            ->where('innerTag.name = :name')
            ->setParameter('name', 'yukyk')
        ;

        $qb = $em->createQueryBuilder();
        $qb->select('t')
            ->from('Oro\Bundle\TagBundle\Entity\Tag', 't')
            ->where($qb->expr()->in('t.id', $subQuery->getQuery()->getDQL()));

        $filter = $em->getFilters()->enable('acl_check');
        $filter->setParameter('table', 'Oro\Bundle\TagBundle\Entity\Tag');

        var_dump($qb->getQuery()->getSQL());
    die;
        $data = $qb->getQuery()
            ->execute();
        return array(
            'data' => $data
        );
    }

    /**
     * @Route("/select-walker", name="acme_demo_select_walker")
     * @Template()
     * @return array
     */
    public function selectWalkerAction()
    {
        $em = $this->getDoctrine()->getManager();
        /** @var \Doctrine\ORM\QueryBuilder $qb */
        $qb = $em->createQueryBuilder();
        /*$qb->select('tag.id')
            ->from('Oro\Bundle\TagBundle\Entity\Tag', 'tag')
            ->leftJoin('Oro\Bundle\UserBundle\Entity\User', 'user', 'WITH', 'tag.id = user.id')
            //->where('innerTag.name = :name')
            //->andWhere('innerTag.id IN (1, 2, 3)')
            //->setParameter('name', 'yukyk')
        ;*/
        $subQuery = $em->createQueryBuilder();
        $subQuery->select('tag.id')
            ->from('Oro\Bundle\TagBundle\Entity\Tag', 'tag')
            ->leftJoin('Oro\Bundle\TagBundle\Entity\Tag', 'innerTag', 'WITH', 'tag.id = innerTag.id')
            ->where('innerTag.name = :name')
            ->setParameter('name', 'yukyk')
        ;

        $qb = $em->createQueryBuilder();
        $qb->select('t', 'u')
            ->from('Oro\Bundle\TagBundle\Entity\Tag', 't')
            ->from('Oro\Bundle\TagBundle\Entity\Tag', 'u')
            ->where($qb->expr()->in('t.id', $subQuery->getQuery()->getDQL()))
            ->andWhere('t.id = u.id')
            ->andWhere('t.id = 112');


        $query = $this->container->get('oro_security.acl_helper')->apply($qb);

        $sql = $query->getSQL();
        var_dump($sql);
        die;
        $data = $query
            ->execute();
        return array(
            'data' => $data
        );
    }

    /**
     * @Route("/export", name="acme_demo_export")
     * @Template()
     * @return array
     */
    public function varExportAction()
    {
        var_dump($this->getDoctrine()->getManager()->getRepository('Oro\Bundle\TagBundle\Entity\Tag')->findAll());
        die;
    }
}
