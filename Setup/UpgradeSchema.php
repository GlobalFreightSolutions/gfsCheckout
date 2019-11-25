<?php

namespace JustShout\Gfs\Setup;

use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\UpgradeSchemaInterface;

/**
 * Upgrade Schema
 *
 * @package   JustShout\Gfs
 * @author    JustShout <http://developer.justshoutgfs.com/>
 * @copyright JustShout - 2019
 */
class UpgradeSchema implements UpgradeSchemaInterface
{
    /**
     * {@inheritDoc}
     *
     * @param SchemaSetupInterface   $setup
     * @param ModuleContextInterface $context
     */
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        switch (true) {
            case !$context->getVersion():
            case version_compare($context->getVersion(), '1.2.0', '<'):
                $this->_addDropPointDetailsColumn($setup);
                break;
        }
    }

    /**
     * Add Drop Point Details Columns
     *
     * @param SchemaSetupInterface $setup
     *
     * @return void
     */
    protected function _addDropPointDetailsColumn(SchemaSetupInterface $setup)
    {
        $setup->getConnection()->addColumn($setup->getTable('sales_order'), 'gfs_drop_point_details', [
            'type'     => Table::TYPE_TEXT,
            'nullable' => true,
            'length'   => '64k',
            'comment'  => 'Gfs Drop Point Data'
        ]);
    }
}
