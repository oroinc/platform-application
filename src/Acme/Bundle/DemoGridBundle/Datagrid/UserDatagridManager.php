<?php

namespace Acme\Bundle\DemoGridBundle\Datagrid;

use Oro\Bundle\GridBundle\Datagrid\DatagridManager;
use Oro\Bundle\GridBundle\Field\FieldDescription;

class UserDatagridManager extends DatagridManager
{
    /**
     * @var array
     */
    protected $datagridFields = array(
        'id' => array(
            'label'      => 'ID',
            'type'       => 'oro_grid_orm_number',
            'field_type' => 'number',
            'field_name' => 'id',
            'sortable'   => true
        ),
        'email' => array(
            'label'      => 'Email',
            'type'       => 'oro_grid_orm_string',
            'field_type' => 'text',
            'field_name' => 'email',
            'sortable'   => true
        ),
        'firstname' => array(
            'label'      => 'Firstname',
            'type'       => 'oro_grid_orm_string',
            'field_type' => 'text',
            'field_name' => 'firstname',
            'sortable'   => true
        ),
        'lastname' => array(
            'label'      => 'Lastname',
            'type'       => 'oro_grid_orm_string',
            'field_type' => 'text',
            'field_name' => 'lastname',
            'sortable'   => false
        ),
    );

    /**
     * {@inheritdoc}
     */
    protected function getListFields()
    {
        $fields = array();
        foreach ($this->datagridFields as $fieldName => $fieldParameters) {
            $field = new FieldDescription();
            $field->setName($fieldName);
            $field->setOption('label', $fieldParameters['label']);
            $fields[] = $field;
        }

        return $fields;
    }

    /**
     * @return array
     */
    protected function getSorters()
    {
        $fields = array();
        foreach ($this->datagridFields as $fieldName => $fieldParameters) {
            if ($fieldParameters['sortable']) {
                $field = new FieldDescription();
                $field->setName($fieldName);
                $fields[] = $field;
            }
        }

        return $fields;
    }

    /**
     * {@inheritdoc}
     */
    protected function getFilters()
    {
        $fields = array();
        foreach ($this->datagridFields as $fieldName => $fieldParameters) {
            $field = new FieldDescription();
            $field->setName($fieldName);
            $field->setType($fieldParameters['type']);
            $field->setOption('label', $fieldParameters['label']);
            $field->setOption('field_type', $fieldParameters['field_type']);
            $field->setOption('field_name', $fieldParameters['field_name']);
            $field->setOption('required', false);
            $fields[] = $field;
        }

        return $fields;
    }
}
