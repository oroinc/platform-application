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
        'email' => array(
            'label'      => 'Email',
            'type'       => 'oro_grid_orm_string',
            'field_type' => 'text',
            'field_name' => 'email',
        ),
        'firstname' => array(
            'label'      => 'Firstname',
            'type'       => 'oro_grid_orm_string',
            'field_type' => 'text',
            'field_name' => 'firstname',
        ),
        'lastname' => array(
            'label'      => 'Lastname',
            'type'       => 'oro_grid_orm_string',
            'field_type' => 'text',
            'field_name' => 'lastname',
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
        $email = new FieldDescription();
        $email->setName('o.email');

        $firstName = new FieldDescription();
        $firstName->setName('o.firstname');

        $lastName = new FieldDescription();
        $lastName->setName('o.lastname');

        return array($email, $firstName, $lastName);
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
