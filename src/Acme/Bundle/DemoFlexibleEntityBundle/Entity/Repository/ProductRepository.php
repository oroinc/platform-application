<?php
namespace Acme\Bundle\DemoFlexibleEntityBundle\Entity\Repository;

use Doctrine\ORM\QueryBuilder;

use Oro\Bundle\FlexibleEntityBundle\Entity\Repository\FlexibleEntityRepository;

/**
 * Product repository
 *
 * @author    Nicolas Dupont <nicolas@akeneo.com>
 * @copyright 2012 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/MIT MIT
 */
class ProductRepository extends FlexibleEntityRepository
{
    /**
     * Add join to values tables
     *
     * @param QueryBuilder $qb
     */
    protected function addJoinToValueTables(QueryBuilder $qb)
    {
        parent::addJoinToValueTables($qb);

        $qb->addSelect('ValueMetric')->addSelect('ValuePrice')->addSelect('ValueMedia');
        $qb->leftJoin('Value.price', 'ValuePrice')
            ->leftJoin('Value.media', 'ValueMedia')
            ->leftJoin('Value.metric', 'ValueMetric');
    }
}
