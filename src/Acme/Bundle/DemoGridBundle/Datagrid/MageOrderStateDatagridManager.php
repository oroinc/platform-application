<?php

namespace Acme\Bundle\DemoGridBundle\Datagrid;

use Symfony\Component\Yaml\Yaml;

use Doctrine\ORM\EntityManager;

use Oro\Bundle\GridBundle\Datagrid\DatagridManager;
use Oro\Bundle\GridBundle\Datagrid\QueryConverter\YamlConverter;
use Oro\Bundle\GridBundle\Field\FieldDescription;
use Oro\Bundle\GridBundle\Field\FieldDescriptionCollection;
use Oro\Bundle\GridBundle\Field\FieldDescriptionInterface;
use Oro\Bundle\GridBundle\Filter\FilterInterface;

class MageOrderStateDatagridManager extends DatagridManager
{
    /**
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * {@inheritDoc}
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    protected function configureFields(FieldDescriptionCollection $fieldsCollection)
    {
        $field = new FieldDescription();

        $field->setName('date');
        $field->setOptions(
            array(
                'type'         => FieldDescriptionInterface::TYPE_DATE,
                'label'        => 'Date',
                'entity_alias' => 'm',
                'field_name'   => 'createdAt',
                'filter_type'  => FilterInterface::TYPE_DATE,
                'required'     => false,
                'sortable'     => true,
                'filterable'   => true,
                'show_filter'  => true,
            )
        );

        $fieldsCollection->add($field);

        $field = new FieldDescription();

        $field->setName('state');
        $field->setOptions(
            array(
                'type'         => FieldDescriptionInterface::TYPE_TEXT,
                'label'        => 'state',
                'entity_alias' => 'm',
                'field_name'   => 'state',
                'filter_type'  => FilterInterface::TYPE_STRING,
                'required'     => false,
                'sortable'     => true,
                'filterable'   => true,
                'show_filter'  => true,
            )
        );

        $fieldsCollection->add($field);

        $field = new FieldDescription();

        $field->setName('orderCount');
        $field->setOptions(
            array(
                'type'         => FieldDescriptionInterface::TYPE_INTEGER,
                'label'        => 'Orders count',
                'field_name'   => 'orderCount',
                'filter_type'  => FilterInterface::TYPE_NUMBER,
                'expression'   => 'SUM(m.orderCount)',
                'required'     => false,
                'sortable'     => true,
                'filterable'   => true,
                'show_filter'  => true,
            )
        );

        $fieldsCollection->add($field);

        $field = new FieldDescription();

        $field->setName('avgTotal');
        $field->setOptions(
            array(
                'type'         => FieldDescriptionInterface::TYPE_DECIMAL,
                'label'        => 'Average total',
                'field_name'   => 'avgTotal',
                'filter_type'  => FilterInterface::TYPE_NUMBER,
                'expression'   => 'AVG(m.avgBaseGrandTotal)',
                'required'     => false,
                'sortable'     => true,
                'filterable'   => true,
                'show_filter'  => true,
            )
        );

        $fieldsCollection->add($field);
    }

    /**
     * {@inheritdoc}
     */
    protected function createQuery()
    {
        $input     = Yaml::parse(file_get_contents(__DIR__ . '/../Resources/config/reports.yml'));
        $converter = new YamlConverter();

        $this->queryFactory->setQueryBuilder(
            $converter->parse($input['reports'][1], $this->entityManager)
        );

        return $this->queryFactory->createQuery();
    }

    /**
     * @param EntityManager $entityManager
     */
    public function setEntityManager(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }
}
