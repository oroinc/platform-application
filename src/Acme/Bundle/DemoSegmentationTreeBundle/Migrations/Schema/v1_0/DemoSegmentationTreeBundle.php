<?php

namespace Acme\Bundle\DemoSegmentationTreeBundle\Migrations\Schema\v1_0;

use Doctrine\DBAL\Schema\Schema;
use Oro\Bundle\MigrationBundle\Migration\Migration;
use Oro\Bundle\MigrationBundle\Migration\QueryBag;

class DemoSegmentationTreeBundle implements Migration
{
    /**
     * @inheritdoc
     */
    public function up(Schema $schema, QueryBag $queries)
    {
        /** Generate table acme_demosegmentationtree_product **/
        $table = $schema->createTable('acme_segmtree_product');
        $table->addColumn('id', 'integer', ['autoincrement' => true]);
        $table->addColumn('name', 'string', ['length' => 64]);
        $table->addColumn('description', 'string', ['length' => 255]);
        $table->setPrimaryKey(['id']);
        /** End of generate table acme_demosegmentationtree_product **/

        /** Generate table acme_demosegmentationtree_productsegment **/
        $table = $schema->createTable('acme_segmenttree_prsegment');
        $table->addColumn('id', 'integer', ['autoincrement' => true]);
        $table->addColumn('parent_id', 'integer', ['notnull' => false]);
        $table->addColumn('code', 'string', ['length' => 64]);
        $table->addColumn('lft', 'integer', []);
        $table->addColumn('lvl', 'integer', []);
        $table->addColumn('rgt', 'integer', []);
        $table->addColumn('root', 'integer', ['notnull' => false]);
        $table->setPrimaryKey(['id']);
        $table->addIndex(['parent_id'], 'IDX_D37BEED9727ACA70', []);
        /** End of generate table acme_demosegmentationtree_productsegment **/

        /** Generate table acme_demosegmentationtree_segments_products **/
        $table = $schema->createTable('acme_segmenttree_segm_prod');
        $table->addColumn('segment_id', 'integer', []);
        $table->addColumn('product_id', 'integer', []);
        $table->setPrimaryKey(['segment_id', 'product_id']);
        $table->addIndex(['segment_id'], 'IDX_BEAE83C2DB296AAD', []);
        $table->addIndex(['product_id'], 'IDX_BEAE83C24584665A', []);
        /** End of generate table acme_demosegmentationtree_segments_products **/

        /** Generate foreign keys for table acme_demosegmentationtree_productsegment **/
        $table = $schema->getTable('acme_segmenttree_prsegment');
        $table->addForeignKeyConstraint(
            $schema->getTable('acme_segmenttree_prsegment'),
            ['parent_id'],
            ['id'],
            ['onDelete' => 'SET NULL', 'onUpdate' => null,]
        );
        /** End of generate foreign keys for table acme_demosegmentationtree_productsegment **/

        /** Generate foreign keys for table acme_demosegmentationtree_segments_products **/
        $table = $schema->getTable('acme_segmenttree_segm_prod');
        $table->addForeignKeyConstraint(
            $schema->getTable('acme_segmtree_product'),
            ['product_id'],
            ['id'],
            ['onDelete' => null, 'onUpdate' => null,]
        );
        $table->addForeignKeyConstraint(
            $schema->getTable('acme_segmenttree_prsegment'),
            ['segment_id'],
            ['id'],
            ['onDelete' => null, 'onUpdate' => null,]
        );
        /** End of generate foreign keys for table acme_demosegmentationtree_segments_products **/
    }
}
