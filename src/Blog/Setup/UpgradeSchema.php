<?php
namespace Mirasvit\Blog\Setup;

use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Ddl\Table;

class UpgradeSchema implements UpgradeSchemaInterface
{
    /**
     * {@inheritdoc}
     */
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
        $connection = $installer->getConnection();

        if (version_compare($context->getVersion(), '1.0.1') < 0) {
            $connection->dropTable($installer->getTable('mst_blog_author'));

            $table = $installer->getConnection()->newTable(
                $installer->getTable('mst_blog_author')
            )->addColumn(
                'author_id',
                Table::TYPE_INTEGER,
                null,
                ['unsigned' => true, 'nullable' => false, 'primary' => true, 'default' => '0'],
                'Author Id'
            )->addColumn(
                'user_id',
                Table::TYPE_INTEGER,
                null,
                ['unsigned' => true, 'nullable' => false, 'primary' => true, 'default' => '0'],
                'User Id'
            )->addColumn(
                'display_name',
                Table::TYPE_TEXT,
                255,
                ['nullable' => true],
                'Display name'
            )->addColumn(
                'image',
                Table::TYPE_TEXT,
                255,
                ['nullable' => true],
                'Image'
            )->addColumn(
                'bio',
                Table::TYPE_TEXT,
                null,
                ['nullable' => true],
                'Bio'
            )->addForeignKey(
                $installer->getFkName(
                    'mst_blog_author',
                    'user_id',
                    'admin_user',
                    'user_id'
                ),
                'user_id',
                $installer->getTable('admin_user'),
                'user_id',
                Table::ACTION_CASCADE
            )->setComment(
                'Authors'
            );

            $installer->getConnection()->createTable($table);
        }
    }
}
