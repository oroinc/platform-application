<?php

namespace Acme\Bundle\DemoGridBundle\Datagrid;

use Oro\Bundle\GridBundle\Datagrid\FlexibleDatagridManager;
use Oro\Bundle\GridBundle\Field\FieldDescription;
use Oro\Bundle\GridBundle\Field\FieldDescriptionCollection;

class UserDatagridManager extends FlexibleDatagridManager
{
    /**
     * @var FieldDescriptionCollection
     */
    protected $fieldsCollection;

    /**
     * @return FieldDescriptionCollection
     */
    protected function getFieldDescriptionCollection()
    {
        if (!$this->fieldsCollection) {
            $this->fieldsCollection = new FieldDescriptionCollection();

            $fieldId = new FieldDescription();
            $fieldId->setName('id');
            $fieldId->setOptions(
                array(
                    'type'        => 'number',
                    'label'       => 'ID',
                    'field_type'  => 'number',
                    'field_name'  => 'id',
                    'filter_type' => 'oro_grid_orm_number',
                    'required'    => false,
                    'sortable'    => true,
                )
            );
            $this->fieldsCollection->add($fieldId);

            $fieldUsername = new FieldDescription();
            $fieldUsername->setName('username');
            $fieldUsername->setOptions(
                array(
                    'type'        => 'text',
                    'label'       => 'Username',
                    'field_type'  => 'text',
                    'field_name'  => 'username',
                    'filter_type' => 'oro_grid_orm_string',
                    'required'    => false,
                    'sortable'    => true,
                )
            );
            $this->fieldsCollection->add($fieldUsername);

            $fieldEmail = new FieldDescription();
            $fieldEmail->setName('email');
            $fieldEmail->setOptions(
                array(
                    'type'        => 'text',
                    'label'       => 'Email',
                    'field_type'  => 'text',
                    'field_name'  => 'email',
                    'filter_type' => 'oro_grid_orm_string',
                    'required'    => false,
                    'sortable'    => true,
                )
            );
            $this->fieldsCollection->add($fieldEmail);

            foreach ($this->getFlexibleAttributes() as $attribute) {
                $filterType = $attribute->getBackendType() == 'options'
                    ? 'oro_grid_orm_flexible_options'
                    : 'oro_grid_orm_flexible_string';
                $sortable = $attribute->getBackendType() != 'options';

                $field = new FieldDescription();
                $field->setName($attribute->getCode());
                $field->setOptions(
                    array(
                        'type'          => $attribute->getBackendType(),
                        'label'         => $attribute->getCode(),
                        'field_type'    => $attribute->getBackendType(),
                        'field_name'    => $attribute->getCode(),
                        'filter_type'   => $filterType,
                        'required'      => false,
                        'sortable'      => $sortable,
                        'flexible_name' => $this->flexibleManager->getFlexibleName()
                    )
                );
                $this->fieldsCollection->add($field);
            }
        }

        return $this->fieldsCollection;
    }

    /**
     * {@inheritdoc}
     */
    protected function getListFields()
    {
        return $this->getFieldDescriptionCollection()->getElements();
    }

    /**
     * {@inheritdoc}
     */
    protected function getSorters()
    {
        $fields = array();
        /** @var $fieldDescription FieldDescription */
        foreach ($this->getFieldDescriptionCollection() as $fieldDescription) {
            if ($fieldDescription->getOption('sortable')) {
                $fields[] = $fieldDescription;
            }
        }

        return $fields;
    }

    /**
     * {@inheritdoc}
     */
    protected function getFilters()
    {
        return $this->getFieldDescriptionCollection()->getElements();
    }
}
