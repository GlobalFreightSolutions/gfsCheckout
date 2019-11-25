<?php

namespace JustShout\Gfs\Setup;

use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

/**
 * Install Schema
 *
 * @package   JustShout\Gfs
 * @author    JustShout <http://developer.justshoutgfs.com/>
 * @copyright JustShout - 2019
 */
class InstallSchema implements InstallSchemaInterface
{
    /**
     * {@inheritdoc}
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();
        $this->_setupQuoteTable($setup);
        $this->_setupOrderTable($setup);
        $this->_setupOrderGridTable($setup);
        $setup->endSetup();
    }

    /**
     * This method will update the quote table
     *
     * @param SchemaSetupInterface $setup
     *
     * @return void
     */
    protected function _setupQuoteTable(SchemaSetupInterface $setup)
    {
        $setup->getConnection()->addColumn($setup->getTable('quote'), 'gfs_shipping_data', [
            'type'     => Table::TYPE_TEXT,
            'nullable' => true,
            'length'   => '64k',
            'comment'  => 'Gfs Shipping Data'
        ]);

        $setup->getConnection()->addColumn($setup->getTable('quote'), 'gfs_checkout_result', [
            'type'     => Table::TYPE_TEXT,
            'nullable' => true,
            'length'   => '64k',
            'comment'  => 'Gfs Checkout Result'
        ]);

        $setup->getConnection()->addColumn($setup->getTable('quote'), 'gfs_session_id', [
            'type'     => Table::TYPE_TEXT,
            'nullable' => true,
            'comment'  => 'Gfs Session Id'
        ]);
    }

    /**
     * This method will update the orders table
     *
     * @param SchemaSetupInterface $setup
     *
     * @return void
     */
    protected function _setupOrderTable(SchemaSetupInterface $setup)
    {
        $setup->getConnection()->addColumn($setup->getTable('sales_order'), 'gfs_shipping_data', [
            'type'     => Table::TYPE_TEXT,
            'nullable' => true,
            'length'   => '64k',
            'comment'  => 'Gfs Shipping Data'
        ]);

        $setup->getConnection()->addColumn($setup->getTable('sales_order'), 'gfs_checkout_result', [
            'type'     => Table::TYPE_TEXT,
            'nullable' => true,
            'length'   => '64k',
            'comment'  => 'Gfs Checkout Result'
        ]);

        $setup->getConnection()->addColumn($setup->getTable('sales_order'), 'gfs_close_checkout', [
            'type'     => Table::TYPE_TEXT,
            'nullable' => true,
            'length'   => '64k',
            'comment'  => 'Gfs Close Checkout'
        ]);

        $setup->getConnection()->addColumn($setup->getTable('sales_order'), 'gfs_despatch_by', [
            'type'     => Table::TYPE_DATETIME,
            'nullable' => true,
            'comment'  => 'Gfs Despatch By'
        ]);

        $setup->getConnection()->addColumn($setup->getTable('sales_order'), 'gfs_session_id', [
            'type'     => Table::TYPE_TEXT,
            'nullable' => true,
            'comment'  => 'Gfs Session Id'
        ]);
    }

    /**
     * This method will update the orders grid table
     *
     * @param SchemaSetupInterface $setup
     *
     * @return void
     */
    protected function _setupOrderGridTable(SchemaSetupInterface $setup)
    {
        $setup->getConnection()->addColumn($setup->getTable('sales_order_grid'), 'gfs_despatch_by', [
            'type'     => Table::TYPE_DATETIME,
            'nullable' => true,
            'comment'  => 'Gfs Despatch By'
        ]);
    }
}
