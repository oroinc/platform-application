UPGRADE FROM 1.9 to 1.10
=======================

- The class Oro\Component\ConfigExpression\Condition\False was renamed to FalseCondition
- The class Oro\Component\ConfigExpression\Condition\True was renamed to TrueCondition
- The class Oro\Bundle\DataGridBundle\Common\Object was renamed to DataObject
- Changed priority in next extensions:
    * Oro\Bundle\DataGridBundle\Extension\Sorter\OrmSorterExtension from -250 to -260 
    * Oro\Bundle\DataGridBundle\Extension\Sorter\PostgresqlGridModifier from -251 to -261
- Removed support PHP version below 5.5.0
