<?php

namespace Acme\Bundle\DemoBundle\Migrations\Schema\v1_0;

use Doctrine\DBAL\Schema\Schema;
use Oro\Bundle\MigrationBundle\Migration\Migration;
use Oro\Bundle\MigrationBundle\Migration\QueryBag;

/**
 * @SuppressWarnings(PHPMD)
 */
class DemoBundle implements Migration
{
    /**
     * @inheritdoc
     */
    public function up(Schema $schema, QueryBag $queries)
    {
        /** Generate table demo_category_product **/
        $table = $schema->createTable('demo_category_product');
        $table->addColumn('product_id', 'integer', []);
        $table->addColumn('category_id', 'integer', []);
        $table->setPrimaryKey(['product_id', 'category_id']);
        $table->addIndex(['product_id'], 'IDX_DF52FFA4584665A', []);
        $table->addIndex(['category_id'], 'IDX_DF52FFA12469DE2', []);
        /** End of generate table demo_category_product **/

        /** Generate table demo_category **/
        $table = $schema->createTable('demo_category');
        $table->addColumn('id', 'integer', ['autoincrement' => true]);
        $table->addColumn('name', 'string', ['length' => 255]);
        $table->setPrimaryKey(['id']);
        /** End of generate table demo_category **/

        /** Generate table demo_customer **/
        $table = $schema->createTable('demo_customer');
        $table->addColumn('id', 'integer', ['autoincrement' => true]);
        $table->addColumn('first_name', 'string', ['length' => 255]);
        $table->addColumn('last_name', 'string', ['length' => 255]);
        $table->addColumn('city', 'string', ['length' => 100]);
        $table->setPrimaryKey(['id']);
        /** End of generate table demo_customer **/

        /** Generate table demo_manufacturer **/
        $table = $schema->createTable('demo_manufacturer');
        $table->addColumn('id', 'integer', ['autoincrement' => true]);
        $table->addColumn('name', 'string', ['length' => 255]);
        $table->setPrimaryKey(['id']);
        /** End of generate table demo_manufacturer **/

        /** Generate table demo_product **/
        $table = $schema->createTable('demo_product');
        $table->addColumn('id', 'integer', ['autoincrement' => true]);
        $table->addColumn('manufacturer_id', 'integer', ['notnull' => false]);
        $table->addColumn('name', 'string', ['length' => 255]);
        $table->addColumn('price', 'decimal', []);
        $table->addColumn('count', 'integer', []);
        $table->addColumn('create_date', 'datetime', ['notnull' => false]);
        $table->addColumn('description', 'text', []);
        $table->setPrimaryKey(['id']);
        $table->addIndex(['manufacturer_id'], 'IDX_14E1506EA23B42D', []);
        /** End of generate table demo_product **/

        /** Generate table demo_product_value **/
        $table = $schema->createTable('demo_product_value');
        $table->addColumn('id', 'integer', ['autoincrement' => true]);
        $table->addColumn('entity_id', 'integer', ['notnull' => false]);
        $table->setPrimaryKey(['id']);
        $table->addIndex(['entity_id'], 'IDX_E84E38B881257D5D', []);
        /** End of generate table demo_product_value **/

        $this->reportMageTables($schema);
        $this->addKeys($schema);
    }

    /**
     * @param Schema $schema
     */
    protected function reportMageTables(Schema $schema)
    {
        /** Generate table oro_report_mage_order_coupon **/
        $table = $schema->createTable('oro_report_mage_order_coupon');
        $table->addColumn('id', 'integer', ['autoincrement' => true]);
        $table->addColumn('coupon_code', 'string', ['notnull' => false, 'length' => 255]);
        $table->addColumn('created_at', 'date', []);
        $table->addColumn('order_count', 'smallint', ['notnull' => false]);
        $table->addColumn('min_base_discount_amount', 'float', ['notnull' => false]);
        $table->addColumn('max_base_discount_amount', 'float', ['notnull' => false]);
        $table->addColumn('avg_base_discount_amount', 'float', ['notnull' => false]);
        $table->addColumn('sum_base_discount_amount', 'float', ['notnull' => false]);
        $table->addColumn('min_base_discount_canceled', 'float', ['notnull' => false]);
        $table->addColumn('max_base_discount_canceled', 'float', ['notnull' => false]);
        $table->addColumn('avg_base_discount_canceled', 'float', ['notnull' => false]);
        $table->addColumn('sum_base_discount_canceled', 'float', ['notnull' => false]);
        $table->addColumn('min_base_discount_invoiced', 'float', ['notnull' => false]);
        $table->addColumn('max_base_discount_invoiced', 'float', ['notnull' => false]);
        $table->addColumn('avg_base_discount_invoiced', 'float', ['notnull' => false]);
        $table->addColumn('sum_base_discount_invoiced', 'float', ['notnull' => false]);
        $table->addColumn('min_base_discount_refunded', 'float', ['notnull' => false]);
        $table->addColumn('max_base_discount_refunded', 'float', ['notnull' => false]);
        $table->addColumn('avg_base_discount_refunded', 'float', ['notnull' => false]);
        $table->addColumn('sum_base_discount_refunded', 'float', ['notnull' => false]);
        $table->addColumn('min_base_grand_total', 'float', ['notnull' => false]);
        $table->addColumn('max_base_grand_total', 'float', ['notnull' => false]);
        $table->addColumn('avg_base_grand_total', 'float', ['notnull' => false]);
        $table->addColumn('sum_base_grand_total', 'float', ['notnull' => false]);
        $table->addColumn('min_base_shipping_amount', 'float', ['notnull' => false]);
        $table->addColumn('max_base_shipping_amount', 'float', ['notnull' => false]);
        $table->addColumn('avg_base_shipping_amount', 'float', ['notnull' => false]);
        $table->addColumn('sum_base_shipping_amount', 'float', ['notnull' => false]);
        $table->addColumn('min_base_shipping_canceled', 'float', ['notnull' => false]);
        $table->addColumn('max_base_shipping_canceled', 'float', ['notnull' => false]);
        $table->addColumn('avg_base_shipping_canceled', 'float', ['notnull' => false]);
        $table->addColumn('sum_base_shipping_canceled', 'float', ['notnull' => false]);
        $table->addColumn('min_base_shipping_invoiced', 'float', ['notnull' => false]);
        $table->addColumn('max_base_shipping_invoiced', 'float', ['notnull' => false]);
        $table->addColumn('avg_base_shipping_invoiced', 'float', ['notnull' => false]);
        $table->addColumn('sum_base_shipping_invoiced', 'float', ['notnull' => false]);
        $table->addColumn('min_base_shipping_refunded', 'float', ['notnull' => false]);
        $table->addColumn('max_base_shipping_refunded', 'float', ['notnull' => false]);
        $table->addColumn('avg_base_shipping_refunded', 'float', ['notnull' => false]);
        $table->addColumn('sum_base_shipping_refunded', 'float', ['notnull' => false]);
        $table->addColumn('min_base_tax_amount', 'float', ['notnull' => false]);
        $table->addColumn('max_base_tax_amount', 'float', ['notnull' => false]);
        $table->addColumn('avg_base_tax_amount', 'float', ['notnull' => false]);
        $table->addColumn('sum_base_tax_amount', 'float', ['notnull' => false]);
        $table->addColumn('min_base_tax_canceled', 'float', ['notnull' => false]);
        $table->addColumn('max_base_tax_canceled', 'float', ['notnull' => false]);
        $table->addColumn('avg_base_tax_canceled', 'float', ['notnull' => false]);
        $table->addColumn('sum_base_tax_canceled', 'float', ['notnull' => false]);
        $table->addColumn('min_base_tax_invoiced', 'float', ['notnull' => false]);
        $table->addColumn('max_base_tax_invoiced', 'float', ['notnull' => false]);
        $table->addColumn('avg_base_tax_invoiced', 'float', ['notnull' => false]);
        $table->addColumn('sum_base_tax_invoiced', 'float', ['notnull' => false]);
        $table->addColumn('min_base_tax_refunded', 'float', ['notnull' => false]);
        $table->addColumn('max_base_tax_refunded', 'float', ['notnull' => false]);
        $table->addColumn('avg_base_tax_refunded', 'float', ['notnull' => false]);
        $table->addColumn('sum_base_tax_refunded', 'float', ['notnull' => false]);
        $table->setPrimaryKey(['id']);
        $table->addIndex(['coupon_code'], 'IDX_COUPON_CODE', []);
        $table->addIndex(['created_at'], 'IDX_CREATED', []);
        /** End of generate table oro_report_mage_order_coupon **/

        /** Generate table oro_report_mage_order_customer **/
        $table = $schema->createTable('oro_report_mage_order_customer');
        $table->addColumn('id', 'integer', ['autoincrement' => true]);
        $table->addColumn('customer_id', 'integer', ['notnull' => false]);
        $table->addColumn('customer_email', 'string', ['notnull' => false, 'length' => 255]);
        $table->addColumn('customer_group_code', 'string', ['notnull' => false, 'length' => 32]);
        $table->addColumn('website_name', 'string', ['notnull' => false, 'length' => 64]);
        $table->addColumn('created_at', 'date', []);
        $table->addColumn('order_count', 'smallint', ['notnull' => false]);
        $table->addColumn('min_base_discount_amount', 'float', ['notnull' => false]);
        $table->addColumn('max_base_discount_amount', 'float', ['notnull' => false]);
        $table->addColumn('avg_base_discount_amount', 'float', ['notnull' => false]);
        $table->addColumn('sum_base_discount_amount', 'float', ['notnull' => false]);
        $table->addColumn('min_base_discount_canceled', 'float', ['notnull' => false]);
        $table->addColumn('max_base_discount_canceled', 'float', ['notnull' => false]);
        $table->addColumn('avg_base_discount_canceled', 'float', ['notnull' => false]);
        $table->addColumn('sum_base_discount_canceled', 'float', ['notnull' => false]);
        $table->addColumn('min_base_discount_invoiced', 'float', ['notnull' => false]);
        $table->addColumn('max_base_discount_invoiced', 'float', ['notnull' => false]);
        $table->addColumn('avg_base_discount_invoiced', 'float', ['notnull' => false]);
        $table->addColumn('sum_base_discount_invoiced', 'float', ['notnull' => false]);
        $table->addColumn('min_base_discount_refunded', 'float', ['notnull' => false]);
        $table->addColumn('max_base_discount_refunded', 'float', ['notnull' => false]);
        $table->addColumn('avg_base_discount_refunded', 'float', ['notnull' => false]);
        $table->addColumn('sum_base_discount_refunded', 'float', ['notnull' => false]);
        $table->addColumn('min_base_grand_total', 'float', ['notnull' => false]);
        $table->addColumn('max_base_grand_total', 'float', ['notnull' => false]);
        $table->addColumn('avg_base_grand_total', 'float', ['notnull' => false]);
        $table->addColumn('sum_base_grand_total', 'float', ['notnull' => false]);
        $table->addColumn('min_base_shipping_amount', 'float', ['notnull' => false]);
        $table->addColumn('max_base_shipping_amount', 'float', ['notnull' => false]);
        $table->addColumn('avg_base_shipping_amount', 'float', ['notnull' => false]);
        $table->addColumn('sum_base_shipping_amount', 'float', ['notnull' => false]);
        $table->addColumn('min_base_shipping_canceled', 'float', ['notnull' => false]);
        $table->addColumn('max_base_shipping_canceled', 'float', ['notnull' => false]);
        $table->addColumn('avg_base_shipping_canceled', 'float', ['notnull' => false]);
        $table->addColumn('sum_base_shipping_canceled', 'float', ['notnull' => false]);
        $table->addColumn('min_base_shipping_invoiced', 'float', ['notnull' => false]);
        $table->addColumn('max_base_shipping_invoiced', 'float', ['notnull' => false]);
        $table->addColumn('avg_base_shipping_invoiced', 'float', ['notnull' => false]);
        $table->addColumn('sum_base_shipping_invoiced', 'float', ['notnull' => false]);
        $table->addColumn('min_base_shipping_refunded', 'float', ['notnull' => false]);
        $table->addColumn('max_base_shipping_refunded', 'float', ['notnull' => false]);
        $table->addColumn('avg_base_shipping_refunded', 'float', ['notnull' => false]);
        $table->addColumn('sum_base_shipping_refunded', 'float', ['notnull' => false]);
        $table->addColumn('min_base_tax_amount', 'float', ['notnull' => false]);
        $table->addColumn('max_base_tax_amount', 'float', ['notnull' => false]);
        $table->addColumn('avg_base_tax_amount', 'float', ['notnull' => false]);
        $table->addColumn('sum_base_tax_amount', 'float', ['notnull' => false]);
        $table->addColumn('min_base_tax_canceled', 'float', ['notnull' => false]);
        $table->addColumn('max_base_tax_canceled', 'float', ['notnull' => false]);
        $table->addColumn('avg_base_tax_canceled', 'float', ['notnull' => false]);
        $table->addColumn('sum_base_tax_canceled', 'float', ['notnull' => false]);
        $table->addColumn('min_base_tax_invoiced', 'float', ['notnull' => false]);
        $table->addColumn('max_base_tax_invoiced', 'float', ['notnull' => false]);
        $table->addColumn('avg_base_tax_invoiced', 'float', ['notnull' => false]);
        $table->addColumn('sum_base_tax_invoiced', 'float', ['notnull' => false]);
        $table->addColumn('min_base_tax_refunded', 'float', ['notnull' => false]);
        $table->addColumn('max_base_tax_refunded', 'float', ['notnull' => false]);
        $table->addColumn('avg_base_tax_refunded', 'float', ['notnull' => false]);
        $table->addColumn('sum_base_tax_refunded', 'float', ['notnull' => false]);
        $table->setPrimaryKey(['id']);
        $table->addIndex(['customer_id'], 'IDX_CUSTOMER_ID', []);
        $table->addIndex(['created_at'], 'IDX_CREATED', []);
        /** End of generate table oro_report_mage_order_customer **/

        /** Generate table oro_report_mage_order_shipping **/
        $table = $schema->createTable('oro_report_mage_order_shipping');
        $table->addColumn('id', 'integer', ['autoincrement' => true]);
        $table->addColumn('shipping_method', 'string', ['notnull' => false, 'length' => 255]);
        $table->addColumn('shipping_description', 'string', ['notnull' => false, 'length' => 255]);
        $table->addColumn('created_at', 'date', []);
        $table->addColumn('order_count', 'smallint', ['notnull' => false]);
        $table->addColumn('min_base_discount_amount', 'float', ['notnull' => false]);
        $table->addColumn('max_base_discount_amount', 'float', ['notnull' => false]);
        $table->addColumn('avg_base_discount_amount', 'float', ['notnull' => false]);
        $table->addColumn('sum_base_discount_amount', 'float', ['notnull' => false]);
        $table->addColumn('min_base_discount_canceled', 'float', ['notnull' => false]);
        $table->addColumn('max_base_discount_canceled', 'float', ['notnull' => false]);
        $table->addColumn('avg_base_discount_canceled', 'float', ['notnull' => false]);
        $table->addColumn('sum_base_discount_canceled', 'float', ['notnull' => false]);
        $table->addColumn('min_base_discount_invoiced', 'float', ['notnull' => false]);
        $table->addColumn('max_base_discount_invoiced', 'float', ['notnull' => false]);
        $table->addColumn('avg_base_discount_invoiced', 'float', ['notnull' => false]);
        $table->addColumn('sum_base_discount_invoiced', 'float', ['notnull' => false]);
        $table->addColumn('min_base_discount_refunded', 'float', ['notnull' => false]);
        $table->addColumn('max_base_discount_refunded', 'float', ['notnull' => false]);
        $table->addColumn('avg_base_discount_refunded', 'float', ['notnull' => false]);
        $table->addColumn('sum_base_discount_refunded', 'float', ['notnull' => false]);
        $table->addColumn('min_base_grand_total', 'float', ['notnull' => false]);
        $table->addColumn('max_base_grand_total', 'float', ['notnull' => false]);
        $table->addColumn('avg_base_grand_total', 'float', ['notnull' => false]);
        $table->addColumn('sum_base_grand_total', 'float', ['notnull' => false]);
        $table->addColumn('min_base_shipping_amount', 'float', ['notnull' => false]);
        $table->addColumn('max_base_shipping_amount', 'float', ['notnull' => false]);
        $table->addColumn('avg_base_shipping_amount', 'float', ['notnull' => false]);
        $table->addColumn('sum_base_shipping_amount', 'float', ['notnull' => false]);
        $table->addColumn('min_base_shipping_canceled', 'float', ['notnull' => false]);
        $table->addColumn('max_base_shipping_canceled', 'float', ['notnull' => false]);
        $table->addColumn('avg_base_shipping_canceled', 'float', ['notnull' => false]);
        $table->addColumn('sum_base_shipping_canceled', 'float', ['notnull' => false]);
        $table->addColumn('min_base_shipping_invoiced', 'float', ['notnull' => false]);
        $table->addColumn('max_base_shipping_invoiced', 'float', ['notnull' => false]);
        $table->addColumn('avg_base_shipping_invoiced', 'float', ['notnull' => false]);
        $table->addColumn('sum_base_shipping_invoiced', 'float', ['notnull' => false]);
        $table->addColumn('min_base_shipping_refunded', 'float', ['notnull' => false]);
        $table->addColumn('max_base_shipping_refunded', 'float', ['notnull' => false]);
        $table->addColumn('avg_base_shipping_refunded', 'float', ['notnull' => false]);
        $table->addColumn('sum_base_shipping_refunded', 'float', ['notnull' => false]);
        $table->addColumn('min_base_tax_amount', 'float', ['notnull' => false]);
        $table->addColumn('max_base_tax_amount', 'float', ['notnull' => false]);
        $table->addColumn('avg_base_tax_amount', 'float', ['notnull' => false]);
        $table->addColumn('sum_base_tax_amount', 'float', ['notnull' => false]);
        $table->addColumn('min_base_tax_canceled', 'float', ['notnull' => false]);
        $table->addColumn('max_base_tax_canceled', 'float', ['notnull' => false]);
        $table->addColumn('avg_base_tax_canceled', 'float', ['notnull' => false]);
        $table->addColumn('sum_base_tax_canceled', 'float', ['notnull' => false]);
        $table->addColumn('min_base_tax_invoiced', 'float', ['notnull' => false]);
        $table->addColumn('max_base_tax_invoiced', 'float', ['notnull' => false]);
        $table->addColumn('avg_base_tax_invoiced', 'float', ['notnull' => false]);
        $table->addColumn('sum_base_tax_invoiced', 'float', ['notnull' => false]);
        $table->addColumn('min_base_tax_refunded', 'float', ['notnull' => false]);
        $table->addColumn('max_base_tax_refunded', 'float', ['notnull' => false]);
        $table->addColumn('avg_base_tax_refunded', 'float', ['notnull' => false]);
        $table->addColumn('sum_base_tax_refunded', 'float', ['notnull' => false]);
        $table->setPrimaryKey(['id']);
        $table->addIndex(['shipping_method'], 'IDX_SHIPPING', []);
        $table->addIndex(['created_at'], 'IDX_CREATED', []);
        /** End of generate table oro_report_mage_order_shipping **/

        /** Generate table oro_report_mage_order_state **/
        $table = $schema->createTable('oro_report_mage_order_state');
        $table->addColumn('id', 'integer', ['autoincrement' => true]);
        $table->addColumn('state', 'string', ['notnull' => false, 'length' => 32]);
        $table->addColumn('created_at', 'date', []);
        $table->addColumn('order_count', 'smallint', ['notnull' => false]);
        $table->addColumn('min_base_discount_amount', 'float', ['notnull' => false]);
        $table->addColumn('max_base_discount_amount', 'float', ['notnull' => false]);
        $table->addColumn('avg_base_discount_amount', 'float', ['notnull' => false]);
        $table->addColumn('sum_base_discount_amount', 'float', ['notnull' => false]);
        $table->addColumn('min_base_discount_canceled', 'float', ['notnull' => false]);
        $table->addColumn('max_base_discount_canceled', 'float', ['notnull' => false]);
        $table->addColumn('avg_base_discount_canceled', 'float', ['notnull' => false]);
        $table->addColumn('sum_base_discount_canceled', 'float', ['notnull' => false]);
        $table->addColumn('min_base_discount_invoiced', 'float', ['notnull' => false]);
        $table->addColumn('max_base_discount_invoiced', 'float', ['notnull' => false]);
        $table->addColumn('avg_base_discount_invoiced', 'float', ['notnull' => false]);
        $table->addColumn('sum_base_discount_invoiced', 'float', ['notnull' => false]);
        $table->addColumn('min_base_discount_refunded', 'float', ['notnull' => false]);
        $table->addColumn('max_base_discount_refunded', 'float', ['notnull' => false]);
        $table->addColumn('avg_base_discount_refunded', 'float', ['notnull' => false]);
        $table->addColumn('sum_base_discount_refunded', 'float', ['notnull' => false]);
        $table->addColumn('min_base_grand_total', 'float', ['notnull' => false]);
        $table->addColumn('max_base_grand_total', 'float', ['notnull' => false]);
        $table->addColumn('avg_base_grand_total', 'float', ['notnull' => false]);
        $table->addColumn('sum_base_grand_total', 'float', ['notnull' => false]);
        $table->addColumn('min_base_shipping_amount', 'float', ['notnull' => false]);
        $table->addColumn('max_base_shipping_amount', 'float', ['notnull' => false]);
        $table->addColumn('avg_base_shipping_amount', 'float', ['notnull' => false]);
        $table->addColumn('sum_base_shipping_amount', 'float', ['notnull' => false]);
        $table->addColumn('min_base_shipping_canceled', 'float', ['notnull' => false]);
        $table->addColumn('max_base_shipping_canceled', 'float', ['notnull' => false]);
        $table->addColumn('avg_base_shipping_canceled', 'float', ['notnull' => false]);
        $table->addColumn('sum_base_shipping_canceled', 'float', ['notnull' => false]);
        $table->addColumn('min_base_shipping_invoiced', 'float', ['notnull' => false]);
        $table->addColumn('max_base_shipping_invoiced', 'float', ['notnull' => false]);
        $table->addColumn('avg_base_shipping_invoiced', 'float', ['notnull' => false]);
        $table->addColumn('sum_base_shipping_invoiced', 'float', ['notnull' => false]);
        $table->addColumn('min_base_shipping_refunded', 'float', ['notnull' => false]);
        $table->addColumn('max_base_shipping_refunded', 'float', ['notnull' => false]);
        $table->addColumn('avg_base_shipping_refunded', 'float', ['notnull' => false]);
        $table->addColumn('sum_base_shipping_refunded', 'float', ['notnull' => false]);
        $table->addColumn('min_base_tax_amount', 'float', ['notnull' => false]);
        $table->addColumn('max_base_tax_amount', 'float', ['notnull' => false]);
        $table->addColumn('avg_base_tax_amount', 'float', ['notnull' => false]);
        $table->addColumn('sum_base_tax_amount', 'float', ['notnull' => false]);
        $table->addColumn('min_base_tax_canceled', 'float', ['notnull' => false]);
        $table->addColumn('max_base_tax_canceled', 'float', ['notnull' => false]);
        $table->addColumn('avg_base_tax_canceled', 'float', ['notnull' => false]);
        $table->addColumn('sum_base_tax_canceled', 'float', ['notnull' => false]);
        $table->addColumn('min_base_tax_invoiced', 'float', ['notnull' => false]);
        $table->addColumn('max_base_tax_invoiced', 'float', ['notnull' => false]);
        $table->addColumn('avg_base_tax_invoiced', 'float', ['notnull' => false]);
        $table->addColumn('sum_base_tax_invoiced', 'float', ['notnull' => false]);
        $table->addColumn('min_base_tax_refunded', 'float', ['notnull' => false]);
        $table->addColumn('max_base_tax_refunded', 'float', ['notnull' => false]);
        $table->addColumn('avg_base_tax_refunded', 'float', ['notnull' => false]);
        $table->addColumn('sum_base_tax_refunded', 'float', ['notnull' => false]);
        $table->setPrimaryKey(['id']);
        $table->addIndex(['state'], 'IDX_STATE', []);
        $table->addIndex(['created_at'], 'IDX_CREATED', []);
        /** End of generate table oro_report_mage_order_state **/

        /** Generate table oro_report_mage_order_status **/
        $table = $schema->createTable('oro_report_mage_order_status');
        $table->addColumn('id', 'integer', ['autoincrement' => true]);
        $table->addColumn('status', 'string', ['notnull' => false, 'length' => 32]);
        $table->addColumn('created_at', 'date', []);
        $table->addColumn('order_count', 'smallint', ['notnull' => false]);
        $table->addColumn('min_base_discount_amount', 'float', ['notnull' => false]);
        $table->addColumn('max_base_discount_amount', 'float', ['notnull' => false]);
        $table->addColumn('avg_base_discount_amount', 'float', ['notnull' => false]);
        $table->addColumn('sum_base_discount_amount', 'float', ['notnull' => false]);
        $table->addColumn('min_base_discount_canceled', 'float', ['notnull' => false]);
        $table->addColumn('max_base_discount_canceled', 'float', ['notnull' => false]);
        $table->addColumn('avg_base_discount_canceled', 'float', ['notnull' => false]);
        $table->addColumn('sum_base_discount_canceled', 'float', ['notnull' => false]);
        $table->addColumn('min_base_discount_invoiced', 'float', ['notnull' => false]);
        $table->addColumn('max_base_discount_invoiced', 'float', ['notnull' => false]);
        $table->addColumn('avg_base_discount_invoiced', 'float', ['notnull' => false]);
        $table->addColumn('sum_base_discount_invoiced', 'float', ['notnull' => false]);
        $table->addColumn('min_base_discount_refunded', 'float', ['notnull' => false]);
        $table->addColumn('max_base_discount_refunded', 'float', ['notnull' => false]);
        $table->addColumn('avg_base_discount_refunded', 'float', ['notnull' => false]);
        $table->addColumn('sum_base_discount_refunded', 'float', ['notnull' => false]);
        $table->addColumn('min_base_grand_total', 'float', ['notnull' => false]);
        $table->addColumn('max_base_grand_total', 'float', ['notnull' => false]);
        $table->addColumn('avg_base_grand_total', 'float', ['notnull' => false]);
        $table->addColumn('sum_base_grand_total', 'float', ['notnull' => false]);
        $table->addColumn('min_base_shipping_amount', 'float', ['notnull' => false]);
        $table->addColumn('max_base_shipping_amount', 'float', ['notnull' => false]);
        $table->addColumn('avg_base_shipping_amount', 'float', ['notnull' => false]);
        $table->addColumn('sum_base_shipping_amount', 'float', ['notnull' => false]);
        $table->addColumn('min_base_shipping_canceled', 'float', ['notnull' => false]);
        $table->addColumn('max_base_shipping_canceled', 'float', ['notnull' => false]);
        $table->addColumn('avg_base_shipping_canceled', 'float', ['notnull' => false]);
        $table->addColumn('sum_base_shipping_canceled', 'float', ['notnull' => false]);
        $table->addColumn('min_base_shipping_invoiced', 'float', ['notnull' => false]);
        $table->addColumn('max_base_shipping_invoiced', 'float', ['notnull' => false]);
        $table->addColumn('avg_base_shipping_invoiced', 'float', ['notnull' => false]);
        $table->addColumn('sum_base_shipping_invoiced', 'float', ['notnull' => false]);
        $table->addColumn('min_base_shipping_refunded', 'float', ['notnull' => false]);
        $table->addColumn('max_base_shipping_refunded', 'float', ['notnull' => false]);
        $table->addColumn('avg_base_shipping_refunded', 'float', ['notnull' => false]);
        $table->addColumn('sum_base_shipping_refunded', 'float', ['notnull' => false]);
        $table->addColumn('min_base_tax_amount', 'float', ['notnull' => false]);
        $table->addColumn('max_base_tax_amount', 'float', ['notnull' => false]);
        $table->addColumn('avg_base_tax_amount', 'float', ['notnull' => false]);
        $table->addColumn('sum_base_tax_amount', 'float', ['notnull' => false]);
        $table->addColumn('min_base_tax_canceled', 'float', ['notnull' => false]);
        $table->addColumn('max_base_tax_canceled', 'float', ['notnull' => false]);
        $table->addColumn('avg_base_tax_canceled', 'float', ['notnull' => false]);
        $table->addColumn('sum_base_tax_canceled', 'float', ['notnull' => false]);
        $table->addColumn('min_base_tax_invoiced', 'float', ['notnull' => false]);
        $table->addColumn('max_base_tax_invoiced', 'float', ['notnull' => false]);
        $table->addColumn('avg_base_tax_invoiced', 'float', ['notnull' => false]);
        $table->addColumn('sum_base_tax_invoiced', 'float', ['notnull' => false]);
        $table->addColumn('min_base_tax_refunded', 'float', ['notnull' => false]);
        $table->addColumn('max_base_tax_refunded', 'float', ['notnull' => false]);
        $table->addColumn('avg_base_tax_refunded', 'float', ['notnull' => false]);
        $table->addColumn('sum_base_tax_refunded', 'float', ['notnull' => false]);
        $table->setPrimaryKey(['id']);
        $table->addIndex(['status'], 'IDX_STATUS', []);
        $table->addIndex(['created_at'], 'IDX_CREATED', []);
        /** End of generate table oro_report_mage_order_status **/

        /** Generate table oro_report_mage_order_store **/
        $table = $schema->createTable('oro_report_mage_order_store');
        $table->addColumn('id', 'integer', ['autoincrement' => true]);
        $table->addColumn('store_id', 'smallint', ['notnull' => false]);
        $table->addColumn('store_name', 'string', ['notnull' => false, 'length' => 255]);
        $table->addColumn('created_at', 'date', []);
        $table->addColumn('order_count', 'smallint', ['notnull' => false]);
        $table->addColumn('min_base_discount_amount', 'float', ['notnull' => false]);
        $table->addColumn('max_base_discount_amount', 'float', ['notnull' => false]);
        $table->addColumn('avg_base_discount_amount', 'float', ['notnull' => false]);
        $table->addColumn('sum_base_discount_amount', 'float', ['notnull' => false]);
        $table->addColumn('min_base_discount_canceled', 'float', ['notnull' => false]);
        $table->addColumn('max_base_discount_canceled', 'float', ['notnull' => false]);
        $table->addColumn('avg_base_discount_canceled', 'float', ['notnull' => false]);
        $table->addColumn('sum_base_discount_canceled', 'float', ['notnull' => false]);
        $table->addColumn('min_base_discount_invoiced', 'float', ['notnull' => false]);
        $table->addColumn('max_base_discount_invoiced', 'float', ['notnull' => false]);
        $table->addColumn('avg_base_discount_invoiced', 'float', ['notnull' => false]);
        $table->addColumn('sum_base_discount_invoiced', 'float', ['notnull' => false]);
        $table->addColumn('min_base_discount_refunded', 'float', ['notnull' => false]);
        $table->addColumn('max_base_discount_refunded', 'float', ['notnull' => false]);
        $table->addColumn('avg_base_discount_refunded', 'float', ['notnull' => false]);
        $table->addColumn('sum_base_discount_refunded', 'float', ['notnull' => false]);
        $table->addColumn('min_base_grand_total', 'float', ['notnull' => false]);
        $table->addColumn('max_base_grand_total', 'float', ['notnull' => false]);
        $table->addColumn('avg_base_grand_total', 'float', ['notnull' => false]);
        $table->addColumn('sum_base_grand_total', 'float', ['notnull' => false]);
        $table->addColumn('min_base_shipping_amount', 'float', ['notnull' => false]);
        $table->addColumn('max_base_shipping_amount', 'float', ['notnull' => false]);
        $table->addColumn('avg_base_shipping_amount', 'float', ['notnull' => false]);
        $table->addColumn('sum_base_shipping_amount', 'float', ['notnull' => false]);
        $table->addColumn('min_base_shipping_canceled', 'float', ['notnull' => false]);
        $table->addColumn('max_base_shipping_canceled', 'float', ['notnull' => false]);
        $table->addColumn('avg_base_shipping_canceled', 'float', ['notnull' => false]);
        $table->addColumn('sum_base_shipping_canceled', 'float', ['notnull' => false]);
        $table->addColumn('min_base_shipping_invoiced', 'float', ['notnull' => false]);
        $table->addColumn('max_base_shipping_invoiced', 'float', ['notnull' => false]);
        $table->addColumn('avg_base_shipping_invoiced', 'float', ['notnull' => false]);
        $table->addColumn('sum_base_shipping_invoiced', 'float', ['notnull' => false]);
        $table->addColumn('min_base_shipping_refunded', 'float', ['notnull' => false]);
        $table->addColumn('max_base_shipping_refunded', 'float', ['notnull' => false]);
        $table->addColumn('avg_base_shipping_refunded', 'float', ['notnull' => false]);
        $table->addColumn('sum_base_shipping_refunded', 'float', ['notnull' => false]);
        $table->addColumn('min_base_tax_amount', 'float', ['notnull' => false]);
        $table->addColumn('max_base_tax_amount', 'float', ['notnull' => false]);
        $table->addColumn('avg_base_tax_amount', 'float', ['notnull' => false]);
        $table->addColumn('sum_base_tax_amount', 'float', ['notnull' => false]);
        $table->addColumn('min_base_tax_canceled', 'float', ['notnull' => false]);
        $table->addColumn('max_base_tax_canceled', 'float', ['notnull' => false]);
        $table->addColumn('avg_base_tax_canceled', 'float', ['notnull' => false]);
        $table->addColumn('sum_base_tax_canceled', 'float', ['notnull' => false]);
        $table->addColumn('min_base_tax_invoiced', 'float', ['notnull' => false]);
        $table->addColumn('max_base_tax_invoiced', 'float', ['notnull' => false]);
        $table->addColumn('avg_base_tax_invoiced', 'float', ['notnull' => false]);
        $table->addColumn('sum_base_tax_invoiced', 'float', ['notnull' => false]);
        $table->addColumn('min_base_tax_refunded', 'float', ['notnull' => false]);
        $table->addColumn('max_base_tax_refunded', 'float', ['notnull' => false]);
        $table->addColumn('avg_base_tax_refunded', 'float', ['notnull' => false]);
        $table->addColumn('sum_base_tax_refunded', 'float', ['notnull' => false]);
        $table->setPrimaryKey(['id']);
        $table->addIndex(['store_id'], 'IDX_STORE', []);
        $table->addIndex(['created_at'], 'IDX_CREATED', []);
        /** End of generate table oro_report_mage_order_store **/

        /** Generate table oro_report_mage_order_zip **/
        $table = $schema->createTable('oro_report_mage_order_zip');
        $table->addColumn('id', 'integer', ['autoincrement' => true]);
        $table->addColumn('country_id', 'string', ['notnull' => false, 'length' => 2]);
        $table->addColumn('zip', 'string', ['notnull' => false, 'length' => 255]);
        $table->addColumn('created_at', 'date', []);
        $table->addColumn('order_count', 'smallint', ['notnull' => false]);
        $table->addColumn('min_base_discount_amount', 'float', ['notnull' => false]);
        $table->addColumn('max_base_discount_amount', 'float', ['notnull' => false]);
        $table->addColumn('avg_base_discount_amount', 'float', ['notnull' => false]);
        $table->addColumn('sum_base_discount_amount', 'float', ['notnull' => false]);
        $table->addColumn('min_base_discount_canceled', 'float', ['notnull' => false]);
        $table->addColumn('max_base_discount_canceled', 'float', ['notnull' => false]);
        $table->addColumn('avg_base_discount_canceled', 'float', ['notnull' => false]);
        $table->addColumn('sum_base_discount_canceled', 'float', ['notnull' => false]);
        $table->addColumn('min_base_discount_invoiced', 'float', ['notnull' => false]);
        $table->addColumn('max_base_discount_invoiced', 'float', ['notnull' => false]);
        $table->addColumn('avg_base_discount_invoiced', 'float', ['notnull' => false]);
        $table->addColumn('sum_base_discount_invoiced', 'float', ['notnull' => false]);
        $table->addColumn('min_base_discount_refunded', 'float', ['notnull' => false]);
        $table->addColumn('max_base_discount_refunded', 'float', ['notnull' => false]);
        $table->addColumn('avg_base_discount_refunded', 'float', ['notnull' => false]);
        $table->addColumn('sum_base_discount_refunded', 'float', ['notnull' => false]);
        $table->addColumn('min_base_grand_total', 'float', ['notnull' => false]);
        $table->addColumn('max_base_grand_total', 'float', ['notnull' => false]);
        $table->addColumn('avg_base_grand_total', 'float', ['notnull' => false]);
        $table->addColumn('sum_base_grand_total', 'float', ['notnull' => false]);
        $table->addColumn('min_base_shipping_amount', 'float', ['notnull' => false]);
        $table->addColumn('max_base_shipping_amount', 'float', ['notnull' => false]);
        $table->addColumn('avg_base_shipping_amount', 'float', ['notnull' => false]);
        $table->addColumn('sum_base_shipping_amount', 'float', ['notnull' => false]);
        $table->addColumn('min_base_shipping_canceled', 'float', ['notnull' => false]);
        $table->addColumn('max_base_shipping_canceled', 'float', ['notnull' => false]);
        $table->addColumn('avg_base_shipping_canceled', 'float', ['notnull' => false]);
        $table->addColumn('sum_base_shipping_canceled', 'float', ['notnull' => false]);
        $table->addColumn('min_base_shipping_invoiced', 'float', ['notnull' => false]);
        $table->addColumn('max_base_shipping_invoiced', 'float', ['notnull' => false]);
        $table->addColumn('avg_base_shipping_invoiced', 'float', ['notnull' => false]);
        $table->addColumn('sum_base_shipping_invoiced', 'float', ['notnull' => false]);
        $table->addColumn('min_base_shipping_refunded', 'float', ['notnull' => false]);
        $table->addColumn('max_base_shipping_refunded', 'float', ['notnull' => false]);
        $table->addColumn('avg_base_shipping_refunded', 'float', ['notnull' => false]);
        $table->addColumn('sum_base_shipping_refunded', 'float', ['notnull' => false]);
        $table->addColumn('min_base_tax_amount', 'float', ['notnull' => false]);
        $table->addColumn('max_base_tax_amount', 'float', ['notnull' => false]);
        $table->addColumn('avg_base_tax_amount', 'float', ['notnull' => false]);
        $table->addColumn('sum_base_tax_amount', 'float', ['notnull' => false]);
        $table->addColumn('min_base_tax_canceled', 'float', ['notnull' => false]);
        $table->addColumn('max_base_tax_canceled', 'float', ['notnull' => false]);
        $table->addColumn('avg_base_tax_canceled', 'float', ['notnull' => false]);
        $table->addColumn('sum_base_tax_canceled', 'float', ['notnull' => false]);
        $table->addColumn('min_base_tax_invoiced', 'float', ['notnull' => false]);
        $table->addColumn('max_base_tax_invoiced', 'float', ['notnull' => false]);
        $table->addColumn('avg_base_tax_invoiced', 'float', ['notnull' => false]);
        $table->addColumn('sum_base_tax_invoiced', 'float', ['notnull' => false]);
        $table->addColumn('min_base_tax_refunded', 'float', ['notnull' => false]);
        $table->addColumn('max_base_tax_refunded', 'float', ['notnull' => false]);
        $table->addColumn('avg_base_tax_refunded', 'float', ['notnull' => false]);
        $table->addColumn('sum_base_tax_refunded', 'float', ['notnull' => false]);
        $table->setPrimaryKey(['id']);
        $table->addIndex(['country_id', 'zip'], 'IDX_COUNTRY_ZIP', []);
        $table->addIndex(['created_at'], 'IDX_CREATED', []);
        /** End of generate table oro_report_mage_order_zip **/

        /** Generate table oro_report_mage_order_zip_month **/
        $table = $schema->createTable('oro_report_mage_order_zipmnth');
        $table->addColumn('id', 'integer', ['autoincrement' => true]);
        $table->addColumn('country_id', 'string', ['notnull' => false, 'length' => 2]);
        $table->addColumn('zip', 'string', ['notnull' => false, 'length' => 255]);
        $table->addColumn('year', 'smallint', ['notnull' => false]);
        $table->addColumn('month', 'smallint', ['notnull' => false]);
        $table->addColumn('order_count', 'smallint', ['notnull' => false]);
        $table->addColumn('min_base_discount_amount', 'float', ['notnull' => false]);
        $table->addColumn('max_base_discount_amount', 'float', ['notnull' => false]);
        $table->addColumn('avg_base_discount_amount', 'float', ['notnull' => false]);
        $table->addColumn('sum_base_discount_amount', 'float', ['notnull' => false]);
        $table->addColumn('min_base_discount_canceled', 'float', ['notnull' => false]);
        $table->addColumn('max_base_discount_canceled', 'float', ['notnull' => false]);
        $table->addColumn('avg_base_discount_canceled', 'float', ['notnull' => false]);
        $table->addColumn('sum_base_discount_canceled', 'float', ['notnull' => false]);
        $table->addColumn('min_base_discount_invoiced', 'float', ['notnull' => false]);
        $table->addColumn('max_base_discount_invoiced', 'float', ['notnull' => false]);
        $table->addColumn('avg_base_discount_invoiced', 'float', ['notnull' => false]);
        $table->addColumn('sum_base_discount_invoiced', 'float', ['notnull' => false]);
        $table->addColumn('min_base_discount_refunded', 'float', ['notnull' => false]);
        $table->addColumn('max_base_discount_refunded', 'float', ['notnull' => false]);
        $table->addColumn('avg_base_discount_refunded', 'float', ['notnull' => false]);
        $table->addColumn('sum_base_discount_refunded', 'float', ['notnull' => false]);
        $table->addColumn('min_base_grand_total', 'float', ['notnull' => false]);
        $table->addColumn('max_base_grand_total', 'float', ['notnull' => false]);
        $table->addColumn('avg_base_grand_total', 'float', ['notnull' => false]);
        $table->addColumn('sum_base_grand_total', 'float', ['notnull' => false]);
        $table->addColumn('min_base_shipping_amount', 'float', ['notnull' => false]);
        $table->addColumn('max_base_shipping_amount', 'float', ['notnull' => false]);
        $table->addColumn('avg_base_shipping_amount', 'float', ['notnull' => false]);
        $table->addColumn('sum_base_shipping_amount', 'float', ['notnull' => false]);
        $table->addColumn('min_base_shipping_canceled', 'float', ['notnull' => false]);
        $table->addColumn('max_base_shipping_canceled', 'float', ['notnull' => false]);
        $table->addColumn('avg_base_shipping_canceled', 'float', ['notnull' => false]);
        $table->addColumn('sum_base_shipping_canceled', 'float', ['notnull' => false]);
        $table->addColumn('min_base_shipping_invoiced', 'float', ['notnull' => false]);
        $table->addColumn('max_base_shipping_invoiced', 'float', ['notnull' => false]);
        $table->addColumn('avg_base_shipping_invoiced', 'float', ['notnull' => false]);
        $table->addColumn('sum_base_shipping_invoiced', 'float', ['notnull' => false]);
        $table->addColumn('min_base_shipping_refunded', 'float', ['notnull' => false]);
        $table->addColumn('max_base_shipping_refunded', 'float', ['notnull' => false]);
        $table->addColumn('avg_base_shipping_refunded', 'float', ['notnull' => false]);
        $table->addColumn('sum_base_shipping_refunded', 'float', ['notnull' => false]);
        $table->addColumn('min_base_tax_amount', 'float', ['notnull' => false]);
        $table->addColumn('max_base_tax_amount', 'float', ['notnull' => false]);
        $table->addColumn('avg_base_tax_amount', 'float', ['notnull' => false]);
        $table->addColumn('sum_base_tax_amount', 'float', ['notnull' => false]);
        $table->addColumn('min_base_tax_canceled', 'float', ['notnull' => false]);
        $table->addColumn('max_base_tax_canceled', 'float', ['notnull' => false]);
        $table->addColumn('avg_base_tax_canceled', 'float', ['notnull' => false]);
        $table->addColumn('sum_base_tax_canceled', 'float', ['notnull' => false]);
        $table->addColumn('min_base_tax_invoiced', 'float', ['notnull' => false]);
        $table->addColumn('max_base_tax_invoiced', 'float', ['notnull' => false]);
        $table->addColumn('avg_base_tax_invoiced', 'float', ['notnull' => false]);
        $table->addColumn('sum_base_tax_invoiced', 'float', ['notnull' => false]);
        $table->addColumn('min_base_tax_refunded', 'float', ['notnull' => false]);
        $table->addColumn('max_base_tax_refunded', 'float', ['notnull' => false]);
        $table->addColumn('avg_base_tax_refunded', 'float', ['notnull' => false]);
        $table->addColumn('sum_base_tax_refunded', 'float', ['notnull' => false]);
        $table->setPrimaryKey(['id']);
        $table->addIndex(['country_id', 'zip'], 'IDX_COUNTRY_ZIP', []);
        $table->addIndex(['year', 'month'], 'IDX_YEAR_MONTH', []);
        /** End of generate table oro_report_mage_order_zip_month **/


        /** Generate table oro_report_mage_order_region **/
        $table = $schema->createTable('oro_report_mage_order_region');
        $table->addColumn('id', 'integer', ['autoincrement' => true]);
        $table->addColumn('country_id', 'string', ['notnull' => false, 'length' => 2]);
        $table->addColumn('region', 'string', ['notnull' => false, 'length' => 255]);
        $table->addColumn('created_at', 'date', []);
        $table->addColumn('order_count', 'smallint', ['notnull' => false]);
        $table->addColumn('min_base_discount_amount', 'float', ['notnull' => false]);
        $table->addColumn('max_base_discount_amount', 'float', ['notnull' => false]);
        $table->addColumn('avg_base_discount_amount', 'float', ['notnull' => false]);
        $table->addColumn('sum_base_discount_amount', 'float', ['notnull' => false]);
        $table->addColumn('min_base_discount_canceled', 'float', ['notnull' => false]);
        $table->addColumn('max_base_discount_canceled', 'float', ['notnull' => false]);
        $table->addColumn('avg_base_discount_canceled', 'float', ['notnull' => false]);
        $table->addColumn('sum_base_discount_canceled', 'float', ['notnull' => false]);
        $table->addColumn('min_base_discount_invoiced', 'float', ['notnull' => false]);
        $table->addColumn('max_base_discount_invoiced', 'float', ['notnull' => false]);
        $table->addColumn('avg_base_discount_invoiced', 'float', ['notnull' => false]);
        $table->addColumn('sum_base_discount_invoiced', 'float', ['notnull' => false]);
        $table->addColumn('min_base_discount_refunded', 'float', ['notnull' => false]);
        $table->addColumn('max_base_discount_refunded', 'float', ['notnull' => false]);
        $table->addColumn('avg_base_discount_refunded', 'float', ['notnull' => false]);
        $table->addColumn('sum_base_discount_refunded', 'float', ['notnull' => false]);
        $table->addColumn('min_base_grand_total', 'float', ['notnull' => false]);
        $table->addColumn('max_base_grand_total', 'float', ['notnull' => false]);
        $table->addColumn('avg_base_grand_total', 'float', ['notnull' => false]);
        $table->addColumn('sum_base_grand_total', 'float', ['notnull' => false]);
        $table->addColumn('min_base_shipping_amount', 'float', ['notnull' => false]);
        $table->addColumn('max_base_shipping_amount', 'float', ['notnull' => false]);
        $table->addColumn('avg_base_shipping_amount', 'float', ['notnull' => false]);
        $table->addColumn('sum_base_shipping_amount', 'float', ['notnull' => false]);
        $table->addColumn('min_base_shipping_canceled', 'float', ['notnull' => false]);
        $table->addColumn('max_base_shipping_canceled', 'float', ['notnull' => false]);
        $table->addColumn('avg_base_shipping_canceled', 'float', ['notnull' => false]);
        $table->addColumn('sum_base_shipping_canceled', 'float', ['notnull' => false]);
        $table->addColumn('min_base_shipping_invoiced', 'float', ['notnull' => false]);
        $table->addColumn('max_base_shipping_invoiced', 'float', ['notnull' => false]);
        $table->addColumn('avg_base_shipping_invoiced', 'float', ['notnull' => false]);
        $table->addColumn('sum_base_shipping_invoiced', 'float', ['notnull' => false]);
        $table->addColumn('min_base_shipping_refunded', 'float', ['notnull' => false]);
        $table->addColumn('max_base_shipping_refunded', 'float', ['notnull' => false]);
        $table->addColumn('avg_base_shipping_refunded', 'float', ['notnull' => false]);
        $table->addColumn('sum_base_shipping_refunded', 'float', ['notnull' => false]);
        $table->addColumn('min_base_tax_amount', 'float', ['notnull' => false]);
        $table->addColumn('max_base_tax_amount', 'float', ['notnull' => false]);
        $table->addColumn('avg_base_tax_amount', 'float', ['notnull' => false]);
        $table->addColumn('sum_base_tax_amount', 'float', ['notnull' => false]);
        $table->addColumn('min_base_tax_canceled', 'float', ['notnull' => false]);
        $table->addColumn('max_base_tax_canceled', 'float', ['notnull' => false]);
        $table->addColumn('avg_base_tax_canceled', 'float', ['notnull' => false]);
        $table->addColumn('sum_base_tax_canceled', 'float', ['notnull' => false]);
        $table->addColumn('min_base_tax_invoiced', 'float', ['notnull' => false]);
        $table->addColumn('max_base_tax_invoiced', 'float', ['notnull' => false]);
        $table->addColumn('avg_base_tax_invoiced', 'float', ['notnull' => false]);
        $table->addColumn('sum_base_tax_invoiced', 'float', ['notnull' => false]);
        $table->addColumn('min_base_tax_refunded', 'float', ['notnull' => false]);
        $table->addColumn('max_base_tax_refunded', 'float', ['notnull' => false]);
        $table->addColumn('avg_base_tax_refunded', 'float', ['notnull' => false]);
        $table->addColumn('sum_base_tax_refunded', 'float', ['notnull' => false]);
        $table->setPrimaryKey(['id']);
        $table->addIndex(['country_id', 'region'], 'IDX_COUNTRY_REGION', []);
        $table->addIndex(['created_at'], 'IDX_CREATED', []);
        /** End of generate table oro_report_mage_order_region **/
    }

    /**
     * @param Schema $schema
     */
    protected function addKeys(Schema $schema)
    {
        /** Generate foreign keys for table demo_category_product **/
        $table = $schema->getTable('demo_category_product');
        $table->addForeignKeyConstraint(
            $schema->getTable('demo_category'),
            ['category_id'],
            ['id'],
            ['onDelete' => 'CASCADE', 'onUpdate' => null,]
        );
        $table->addForeignKeyConstraint(
            $schema->getTable('demo_product'),
            ['product_id'],
            ['id'],
            ['onDelete' => 'CASCADE', 'onUpdate' => null,]
        );
        /** End of generate foreign keys for table demo_category_product **/

        /** Generate foreign keys for table demo_product **/
        $table = $schema->getTable('demo_product');
        $table->addForeignKeyConstraint(
            $schema->getTable('demo_manufacturer'),
            ['manufacturer_id'],
            ['id'],
            ['onDelete' => null, 'onUpdate' => null,]
        );
        /** End of generate foreign keys for table demo_product **/

        /** Generate foreign keys for table demo_product **/
        $table = $schema->getTable('demo_product');
        $table->addForeignKeyConstraint(
            $schema->getTable('demo_manufacturer'),
            ['manufacturer_id'],
            ['id'],
            ['onDelete' => null, 'onUpdate' => null,]
        );
        /** End of generate foreign keys for table demo_product **/

        /** Generate foreign keys for table demo_product_value **/
        $table = $schema->getTable('demo_product_value');
        $table->addForeignKeyConstraint(
            $schema->getTable('demo_product'),
            ['entity_id'],
            ['id'],
            ['onDelete' => null, 'onUpdate' => null,]
        );
        /** End of generate foreign keys for table demo_product_value **/
    }
}
