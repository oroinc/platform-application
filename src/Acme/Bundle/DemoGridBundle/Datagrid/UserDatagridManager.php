<?php

namespace Acme\Bundle\DemoGridBundle\Datagrid;

use Oro\Bundle\GridBundle\Datagrid\DatagridManager;
use Oro\Bundle\GridBundle\Field\FieldDescription;

class UserDatagridManager extends DatagridManager
{
    /**
     * {@inheritdoc}
     */
    protected function getListFields()
    {
        $email = new FieldDescription();
        $email->setName('email');
        $email->setOption('label', 'Email');

        $firstName = new FieldDescription();
        $firstName->setName('firstname');
        $firstName->setOption('label', 'Firstname');

        $lastName = new FieldDescription();
        $lastName->setName('lastname');
        $lastName->setOption('label', 'Lastname');

        return array($email, $firstName, $lastName);
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
}
