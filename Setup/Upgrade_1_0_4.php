<?php

namespace Mirasvit\Blog\Setup;

use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

class Upgrade_1_0_4
{
    /**
     * @param SchemaSetupInterface   $setup
     * @param ModuleContextInterface $context
     *
     * @return void
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public static function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->getConnection()->addColumn(
            $installer->getTable('mst_blog_category_entity'),
            'sort_order',
            [
                'type'     => Table::TYPE_INTEGER,
                'length'   => null,
                'unsigned' => true,
                'nullable' => false,
                'default'  => 0,
                'comment'  => 'Order',
            ]
        );
    }
}
