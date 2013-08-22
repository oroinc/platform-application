<?php

namespace Acme\Bundle\DemoBundle\Command\Cron;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use Doctrine\ORM\Query;
use Doctrine\DBAL\ConnectionException;

use Oro\Bundle\CronBundle\Command\CronCommandInterface;

class MageReportCommand extends ContainerAwareCommand implements CronCommandInterface
{
    public function getDefaultDefinition()
    {
        return '*/5 * * * *';
    }

    protected function configure()
    {
        $this
            ->setName('oro:cron:mage-report')
            ->setDescription('Magento reports preparation util');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->getTargetConn()->getConfiguration()->setSQLLogger(null);

        $this->processOrderData($output, 'oro_report_mage_order_state', array('state'));
        $this->processOrderData($output, 'oro_report_mage_order_status', array('status'));
        $this->processOrderData($output, 'oro_report_mage_order_coupon', array('coupon_code'));
        $this->processOrderData($output, 'oro_report_mage_order_store', array('store_id', 'store_name'), array('store_id'));
        $this->processOrderData($output, 'oro_report_mage_order_shipping', array('shipping_method', 'shipping_description'));
        $this->processOrderData(
            $output,
            'oro_report_mage_order_region',
            array('a.`country_id`', 'a.`region`'),
            array('a.`country_id`', 'a.`region`'),
            true
        );
//        $this->processOrderData(
//            $output,
//            'oro_report_mage_order_zip',
//            array('a.`country_id`', 'a.`postcode` zip'),
//            array('a.`country_id`', 'a.`postcode`'),
//            true
//        );
    }

    protected function processOrderData(OutputInterface $output, $table, $fields, $group = array(), $address = false)
    {
        $output->writeln(sprintf('  Perform "<info>%s</info>" import', $table));

        $data = &$this->getOrderData($fields, $group, $address);

        $output->writeln($this->importData($table, $data)
            ? sprintf('  Records processed: <info>%u</info>', count($data))
            : '  <error>Records processing failed</error>'
        );
    }

    /**
     *
     * @param  array $fields  Custom fields to select
     * @param  array $group   Custom group fields. Equal to $fields by default.
     * @param  bool  $address Join address table or not
     * @return array
     */
    protected function &getOrderData($fields, $group = array(), $address = false)
    {
        if (empty($group)) {
            $group = $fields;
        }

        $query = "
            SELECT
                %s, DATE(o.`created_at`) AS `created_at`, COUNT(o.`entity_id`) AS `order_count`,
                MIN(o.`base_discount_amount`) `min_base_discount_amount`, MAX(o.`base_discount_amount`) `max_base_discount_amount`, AVG(o.`base_discount_amount`) `avg_base_discount_amount`, SUM(o.`base_discount_amount`) `sum_base_discount_amount`,
                MIN(o.`base_discount_canceled`) `min_base_discount_canceled`, MAX(o.`base_discount_canceled`) `max_base_discount_canceled`, AVG(o.`base_discount_canceled`) `avg_base_discount_canceled`, SUM(o.`base_discount_canceled`) `sum_base_discount_canceled`,
                MIN(o.`base_discount_invoiced`) `min_base_discount_invoiced`, MAX(o.`base_discount_invoiced`) `max_base_discount_invoiced`, AVG(o.`base_discount_invoiced`) `avg_base_discount_invoiced`, SUM(o.`base_discount_invoiced`) `sum_base_discount_invoiced`,
                MIN(o.`base_discount_refunded`) `min_base_discount_refunded`, MAX(o.`base_discount_refunded`) `max_base_discount_refunded`, AVG(o.`base_discount_refunded`) `avg_base_discount_refunded`, SUM(o.`base_discount_refunded`) `sum_base_discount_refunded`,
                MIN(o.`base_grand_total`) `min_base_grand_total`, MAX(o.`base_grand_total`) `max_base_grand_total`, AVG(o.`base_grand_total`) `avg_base_grand_total`, SUM(o.`base_grand_total`) `sum_base_grand_total`,
                MIN(o.`base_shipping_amount`) `min_base_shipping_amount`, MAX(o.`base_shipping_amount`) `max_base_shipping_amount`, AVG(o.`base_shipping_amount`) `avg_base_shipping_amount`, SUM(o.`base_shipping_amount`) `sum_base_shipping_amount`,
                MIN(o.`base_shipping_canceled`) `min_base_shipping_canceled`, MAX(o.`base_shipping_canceled`) `max_base_shipping_canceled`, AVG(o.`base_shipping_canceled`) `avg_base_shipping_canceled`, SUM(o.`base_shipping_canceled`) `sum_base_shipping_canceled`,
                MIN(o.`base_shipping_invoiced`) `min_base_shipping_invoiced`, MAX(o.`base_shipping_invoiced`) `max_base_shipping_invoiced`, AVG(o.`base_shipping_invoiced`) `avg_base_shipping_invoiced`, SUM(o.`base_shipping_invoiced`) `sum_base_shipping_invoiced`,
                MIN(o.`base_shipping_refunded`) `min_base_shipping_refunded`, MAX(o.`base_shipping_refunded`) `max_base_shipping_refunded`, AVG(o.`base_shipping_refunded`) `avg_base_shipping_refunded`, SUM(o.`base_shipping_refunded`) `sum_base_shipping_refunded`,
                MIN(o.`base_tax_amount`) `min_base_tax_amount`, MAX(o.`base_tax_amount`) `max_base_tax_amount`, AVG(o.`base_tax_amount`) `avg_base_tax_amount`, SUM(o.`base_tax_amount`) `sum_base_tax_amount`,
                MIN(o.`base_tax_canceled`) `min_base_tax_canceled`, MAX(o.`base_tax_canceled`) `max_base_tax_canceled`, AVG(o.`base_tax_canceled`) `avg_base_tax_canceled`, SUM(o.`base_tax_canceled`) `sum_base_tax_canceled`,
                MIN(o.`base_tax_invoiced`) `min_base_tax_invoiced`, MAX(o.`base_tax_invoiced`) `max_base_tax_invoiced`, AVG(o.`base_tax_invoiced`) `avg_base_tax_invoiced`, SUM(o.`base_tax_invoiced`) `sum_base_tax_invoiced`,
                MIN(o.`base_tax_refunded`) `min_base_tax_refunded`, MAX(o.`base_tax_refunded`) `max_base_tax_refunded`, AVG(o.`base_tax_refunded`) `avg_base_tax_refunded`, SUM(o.`base_tax_refunded`) `sum_base_tax_refunded`
            FROM
                sales_flat_order o
        ";

        if ($address) {
            $query .= "
                LEFT JOIN sales_flat_order_address a
                ON o.`entity_id` = a.`parent_id`
            ";
        }

        $query .= "
            GROUP BY
                %s, DATE(o.`created_at`)
        ";

        $stmt = $this->getSourceConn()->prepare(sprintf($query, implode(', ', $fields), implode(', ', $group)));

        $stmt->execute();

        $data = & $stmt->fetchAll(Query::HYDRATE_ARRAY);

        $stmt->closeCursor();

        return $data;
    }

    /**
     *
     * @param  string $table Table name
     * @param  array  $data  Data to import
     * @return bool
     */
    protected function importData($table, $data)
    {
        $conn = $this->getTargetConn();

        $conn->beginTransaction();

        $stmt = $conn->prepare(sprintf("TRUNCATE TABLE %s", $table));

        $stmt->execute();
        $stmt->closeCursor();

        $query  = "INSERT INTO %s VALUES (" . str_repeat('?, ', count($data[0])) . " ?)";
        $stmt   = $conn->prepare(sprintf($query, $table));

        foreach ($data as $rec) {
            $i = 1;

            // first param always an autogenerated id
            $stmt->bindValue($i++, null);

            foreach ($rec as $field) {
                $stmt->bindValue($i++, $field);
            }

            $stmt->execute();
            $stmt->closeCursor();
        }

        try {
            $conn->commit();
        } catch (ConnectionException $e) {
            $conn->rollBack();

            return false;
        }

        return true;
    }

    /**
     * @return \Doctrine\DBAL\Connection
     */
    protected function getTargetConn()
    {
        return $this->getContainer()->get('doctrine.dbal.report_target_connection');
    }

    /**
     * Source (Magento) DB connection
     *
     * @return \Doctrine\DBAL\Connection
     */
    protected function getSourceConn()
    {
        return $this->getContainer()->get('doctrine.dbal.report_source_connection');
    }
}