<?php

namespace Acme\Bundle\DemoGridBundle\Datagrid;

use Doctrine\ORM\EntityManager;

use Oro\Bundle\GridBundle\Datagrid\DatagridManager;
use Oro\Bundle\GridBundle\Field\FieldDescription;
use Oro\Bundle\GridBundle\Field\FieldDescriptionCollection;
use Oro\Bundle\GridBundle\Field\FieldDescriptionInterface;
use Oro\Bundle\GridBundle\Filter\FilterInterface;

class ProductDatagridManager extends DatagridManager
{
    /**
     * {@inheritDoc}
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    protected function configureFields(FieldDescriptionCollection $fieldsCollection)
    {
        $fieldManufacturerId = new FieldDescription();
        $fieldManufacturerId->setName('m_id');
        $fieldManufacturerId->setOptions(
            array(
                'type'         => FieldDescriptionInterface::TYPE_INTEGER,
                'label'        => 'Manufacturer ID',
                'entity_alias' => 'm',
                'field_name'   => 'id',
                'filter_type'  => FilterInterface::TYPE_NUMBER,
                'required'     => false,
                'sortable'     => true,
                'filterable'   => true,
                'show_filter'  => true,
            )
        );
        $fieldsCollection->add($fieldManufacturerId);

        $fieldManufacturerName = new FieldDescription();
        $fieldManufacturerName->setName('m_name');
        $fieldManufacturerName->setOptions(
            array(
                'type'         => FieldDescriptionInterface::TYPE_TEXT,
                'label'        => 'Manufacturer name',
                'entity_alias' => 'm',
                'field_name'   => 'name',
                'filter_type'  => FilterInterface::TYPE_STRING,
                'required'     => false,
                'sortable'     => true,
                'filterable'   => true,
                'show_filter'  => true,
            )
        );
        $fieldsCollection->add($fieldManufacturerName);

        $fieldManufacturerId = new FieldDescription();
        $fieldManufacturerId->setName('product_count');
        $fieldManufacturerId->setOptions(
            array(
                'type'        => FieldDescriptionInterface::TYPE_INTEGER,
                'label'       => 'Number of products',
                'filter_type' => FilterInterface::TYPE_NUMBER,
                'field_name'  => 'product_count',
                'required'    => false,
                'expression'  => 'COUNT(p.id)',
                'sortable'    => true,
                'filterable'  => true,
                'show_filter' => true,
            )
        );
        $fieldsCollection->add($fieldManufacturerId);

        $fieldId = new FieldDescription();
        $fieldId->setName('id');
        $fieldId->setOptions(
            array(
                'type'         => FieldDescriptionInterface::TYPE_INTEGER,
                'label'        => 'Product ID',
                'entity_alias' => 'p',
                'field_name'   => 'id',
                'filter_type'  => FilterInterface::TYPE_NUMBER,
                'required'     => false,
                'sortable'     => true,
                'filterable'   => true,
                'show_filter'  => true,
            )
        );
        $fieldsCollection->add($fieldId);

        $fieldName = new FieldDescription();
        $fieldName->setName('name');
        $fieldName->setOptions(
            array(
                'type'         => FieldDescriptionInterface::TYPE_TEXT,
                'label'        => 'Name',
                'entity_alias' => 'p',
                'field_name'   => 'name',
                'filter_type'  => FilterInterface::TYPE_STRING,
                'required'     => false,
                'sortable'     => true,
                'filterable'   => true,
                'show_filter'  => true,
            )
        );
        $fieldsCollection->add($fieldName);

        $fieldPrice = new FieldDescription();
        $fieldPrice->setName('price');
        $fieldPrice->setOptions(
            array(
                'type'         => FieldDescriptionInterface::TYPE_DECIMAL,
                'label'        => 'Price',
                'entity_alias' => 'p',
                'field_name'   => 'price',
                'filter_type'  => FilterInterface::TYPE_NUMBER,
                'required'     => false,
                'sortable'     => true,
                'filterable'   => true,
                'show_filter'  => true,
            )
        );
        $fieldsCollection->add($fieldPrice);

        $fieldCount = new FieldDescription();
        $fieldCount->setName('count');
        $fieldCount->setOptions(
            array(
                'type'         => FieldDescriptionInterface::TYPE_INTEGER,
                'label'        => 'Count',
                'entity_alias' => 'p',
                'field_name'   => 'count',
                'filter_type'  => FilterInterface::TYPE_NUMBER,
                'required'     => false,
                'sortable'     => true,
                'filterable'   => true,
                'show_filter'  => true,
            )
        );
        $fieldsCollection->add($fieldCount);

        $fieldDescription = new FieldDescription();
        $fieldDescription->setName('description');
        $fieldDescription->setOptions(
            array(
                'type'         => FieldDescriptionInterface::TYPE_TEXT,
                'label'        => 'Description',
                'entity_alias' => 'p',
                'field_name'   => 'description',
                'filter_type'  => FilterInterface::TYPE_STRING,
                'required'     => false,
                'sortable'     => true,
                'filterable'   => true,
                'show_filter'  => true,
            )
        );
        $fieldsCollection->add($fieldDescription);

        $fieldCreateDate = new FieldDescription();
        $fieldCreateDate->setName('createDate');
        $fieldCreateDate->setOptions(
            array(
                'type'         => FieldDescriptionInterface::TYPE_DATETIME,
                'label'        => 'Create Date',
                'entity_alias' => 'p',
                'field_name'   => 'createDate',
                'filter_type'  => FilterInterface::TYPE_DATETIME,
                'required'     => false,
                'sortable'     => true,
                'filterable'   => true,
                'show_filter'  => true,
            )
        );
        $fieldsCollection->add($fieldCreateDate);
    }

    /**
     * {@inheritdoc}
     */
    protected function createQuery()
    {
        $queryBuilder = $this->entityManager->createQueryBuilder();
        $queryBuilder
            ->select(
                'm.id AS m_id',
                'm.name AS m_name',
                'p.id',
                'p.name',
                'p.count',
                'p.price',
                'p.description',
                'p.createDate'
            )
            ->from('AcmeDemoBundle:Manufacturer', 'm')
            ->leftJoin('m.products', 'p')
            ->groupBy('m.id');

        $this->queryFactory->setQueryBuilder($queryBuilder);
        $query = $this->queryFactory->createQuery();
        $query->addSelect('COUNT(p.id) AS product_count', true);

        return $query;
    }

    /**
     * @param EntityManager $entityManager
     */
    public function setEntityManager(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }
}
