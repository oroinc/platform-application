<?php

namespace Acme\Bundle\DemoGridBundle\Datagrid;

use Oro\Bundle\FlexibleEntityBundle\Model\AbstractAttribute;
use Oro\Bundle\GridBundle\Datagrid\FlexibleDatagridManager;
use Oro\Bundle\GridBundle\Field\FieldDescription;
use Oro\Bundle\GridBundle\Field\FieldDescriptionCollection;
use Oro\Bundle\GridBundle\Field\FieldDescriptionInterface;
use Oro\Bundle\GridBundle\Filter\FilterInterface;
use Oro\Bundle\GridBundle\Action\ActionInterface;
use Oro\Bundle\GridBundle\Property\UrlProperty;

class UserDatagridManager extends FlexibleDatagridManager
{
    /**
     * {@inheritDoc}
     */
    protected function getProperties()
    {
        return array(
            new UrlProperty('update_link', $this->router, 'oro_user_update', array('id')),
            new UrlProperty('delete_link', $this->router, 'oro_api_delete_user', array('id')),
        );
    }

    /**
     * {@inheritDoc}
     */
    protected function configureFields(FieldDescriptionCollection $fieldsCollection)
    {
        $demoFieldId = new FieldDescription();
        $demoFfieldId->setName('id');
        $demoFfieldId->setOptions(
            array(
                'type'        => FieldDescriptionInterface::TYPE_INTEGER,
                'label'       => 'ID',
                'field_name'  => 'id',
                'filter_type' => FilterInterface::TYPE_NUMBER,
                'required'    => false,
                'sortable'    => true,
                'filterable'  => true,
                'show_filter' => true,
            )
        );
        $fieldsCollection->add($demoFfieldId);

        $demoFfieldUsername = new FieldDescription();
        $demoFfieldUsername->setName('username');
        $demoFfieldUsername->setOptions(
            array(
                'type'        => FieldDescriptionInterface::TYPE_TEXT,
                'label'       => 'Username',
                'field_name'  => 'username',
                'filter_type' => FilterInterface::TYPE_STRING,
                'required'    => false,
                'sortable'    => true,
                'filterable'  => true,
                'show_filter' => true,
            )
        );
        $fieldsCollection->add($demoFfieldUsername);

        $demoFfieldEmail = new FieldDescription();
        $demoFfieldEmail->setName('email');
        $demoFfieldEmail->setOptions(
            array(
                'type'        => FieldDescriptionInterface::TYPE_TEXT,
                'label'       => 'Email',
                'field_name'  => 'email',
                'filter_type' => FilterInterface::TYPE_STRING,
                'required'    => false,
                'sortable'    => true,
                'filterable'  => true,
                'show_filter' => true,
            )
        );
        $fieldsCollection->add($demoFfieldEmail);

        $demoFfieldFirstName = new FieldDescription();
        $demoFfieldFirstName->setName('firstName');
        $demoFfieldFirstName->setOptions(
            array(
                'type'        => FieldDescriptionInterface::TYPE_TEXT,
                'label'       => 'First name',
                'field_name'  => 'firstName',
                'filter_type' => FilterInterface::TYPE_STRING,
                'required'    => false,
                'sortable'    => true,
                'filterable'  => true,
                'show_filter' => true,
            )
        );
        $demoFfieldsCollection->add($fieldFirstName);

        $demoFfieldLastName = new FieldDescription();
        $demoFfieldLastName->setName('lastName');
        $demoFfieldLastName->setOptions(
            array(
                'type'        => FieldDescriptionInterface::TYPE_TEXT,
                'label'       => 'Last name',
                'field_name'  => 'lastName',
                'filter_type' => FilterInterface::TYPE_STRING,
                'required'    => false,
                'sortable'    => true,
                'filterable'  => true,
                'show_filter' => true,
            )
        );
        $fieldsCollection->add($demoFfieldLastName);

        $demoFfieldBirthday = new FieldDescription();
        $demoFfieldBirthday->setName('birthday');
        $demoFfieldBirthday->setOptions(
            array(
                'type'        => FieldDescriptionInterface::TYPE_DATE,
                'label'       => 'Birthday',
                'field_name'  => 'birthday',
                'filter_type' => FilterInterface::TYPE_DATE,
                'required'    => false,
                'sortable'    => true,
                'filterable'  => true,
                'show_filter' => true,
            )
        );
        $fieldsCollection->add($demoFfieldBirthday);

        $demoFfieldLastName = new FieldDescription();
        $demoFfieldLastName->setName('enabled');
        $demoFfieldLastName->setOptions(
            array(
                'type'        => FieldDescriptionInterface::TYPE_BOOLEAN,
                'label'       => 'Enabled',
                'field_name'  => 'enabled',
                'filter_type' => FilterInterface::TYPE_BOOLEAN,
                'required'    => false,
                'sortable'    => true,
                'filterable'  => true,
                'show_filter' => true,
                /*
                'field_options' => array(
                    'choices' => array(
                        \Oro\Bundle\FilterBundle\Form\Type\Filter\BooleanFilterType::TYPE_YES => 'true',
                        \Oro\Bundle\FilterBundle\Form\Type\Filter\BooleanFilterType::TYPE_NO  => 'false',
                    )
                )
                */
            )
        );
        $fieldsCollection->add($demoFfieldLastName);

        $this->configureFlexibleFields($fieldsCollection, array('gender' => array('multiple' => false)));

        $demoFfieldCreated = new FieldDescription();
        $demoFfieldCreated->setName('created');
        $demoFfieldCreated->setOptions(
            array(
                'type'        => FieldDescriptionInterface::TYPE_DATETIME,
                'label'       => 'Created At',
                'field_name'  => 'created',
                'filter_type' => FilterInterface::TYPE_DATETIME,
                'required'    => false,
                'sortable'    => true,
                'filterable'  => true,
                'show_filter' => true,
            )
        );
        $fieldsCollection->add($demoFfieldCreated);

        $demoFfieldUpdated = new FieldDescription();
        $demoFfieldUpdated->setName('updated');
        $demoFfieldUpdated->setOptions(
            array(
                'type'        => FieldDescriptionInterface::TYPE_DATETIME,
                'label'       => 'Updated At',
                'field_name'  => 'updated',
                'filter_type' => FilterInterface::TYPE_DATETIME,
                'required'    => false,
                'sortable'    => true,
                'filterable'  => true,
                'show_filter' => true,
            )
        );
        $fieldsCollection->add($demoFieldUpdated);
    }


    /**
     * {@inheritDoc}
     */
    protected function getRowActions()
    {
        $updateAction = array(
            'name'         => 'update',
            'type'         => ActionInterface::TYPE_REDIRECT,
            'acl_resource' => 'root',
            'options'      => array(
                'label'=> 'Update',
                'icon' => 'edit',
                'link' => 'update_link',
            )
        );

        $deleteAction = array(
            'name'         => 'delete',
            'type'         => ActionInterface::TYPE_DELETE,
            'acl_resource' => 'root',
            'options'      => array(
                'label'=> 'Delete',
                'icon' => 'trash',
                'link' => 'delete_link',
            )
        );

        return array($updateAction, $deleteAction);
    }
}
