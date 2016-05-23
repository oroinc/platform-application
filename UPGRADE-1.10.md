UPGRADE FROM 1.9 to 1.10
=======================

- The class Oro\Component\ConfigExpression\Condition\False was renamed to FalseCondition
- The class Oro\Component\ConfigExpression\Condition\True was renamed to TrueCondition
- The class Oro\Bundle\DataGridBundle\Common\Object was renamed to DataObject
- Changed priority in next extensions:
    * Oro\Bundle\DataGridBundle\Extension\Sorter\OrmSorterExtension from -250 to -260 
    * Oro\Bundle\DataGridBundle\Extension\Sorter\PostgresqlGridModifier from -251 to -261
- For php version from 7.0.0 to 7.0.5 we replaced `Symfony\Component\Security\Acl\Domain\Entry` on `Oro\Bundle\SecurityBundle\Acl\Domain\Entry` to avoid [bug](https://bugs.php.net/bug.php?id=71940) with unserialization of an object reference
- Removed support PHP version below 5.5.9
