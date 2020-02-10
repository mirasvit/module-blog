<?php

namespace Mirasvit\Blog\Setup;

use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\UpgradeSchemaInterface;

class UpgradeSchema implements UpgradeSchemaInterface
{
    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer  = $setup;
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

        if (version_compare($context->getVersion(), '1.0.2') < 0) {
            $connection->dropTable($installer->getTable('mst_blog_post_product'));

            $table = $installer->getConnection()
                ->newTable($installer->getTable('mst_blog_post_product'))
                ->addColumn(
                    'post_id',
                    Table::TYPE_INTEGER,
                    null,
                    ['unsigned' => true, 'nullable' => false, 'primary' => true, 'default' => '0'],
                    'Post ID'
                )->addColumn(
                    'product_id',
                    Table::TYPE_INTEGER,
                    null,
                    ['unsigned' => true, 'nullable' => false, 'primary' => true, 'default' => '0'],
                    'Product ID'
                )->addColumn(
                    'position',
                    Table::TYPE_INTEGER,
                    null,
                    ['unsigned' => true, 'nullable' => true, 'default' => '0'],
                    'Position'
                )->addIndex(
                    $installer->getIdxName('mst_blog_post_product', ['post_id']),
                    ['post_id']
                )->addIndex(
                    $installer->getIdxName('mst_blog_post_product', ['product_id']),
                    ['product_id']
                )->addForeignKey(
                    $installer->getFkName(
                        'mst_blog_post_product',
                        'product_id',
                        'catalog_product_entity',
                        'entity_id'
                    ),
                    'product_id',
                    $installer->getTable('catalog_product_entity'),
                    'entity_id',
                    Table::ACTION_CASCADE
                )->addForeignKey(
                    $installer->getFkName(
                        'mst_blog_post_product',
                        'post_id',
                        'mst_blog_post_entity',
                        'entity_id'
                    ),
                    'post_id',
                    $installer->getTable('mst_blog_post_entity'),
                    'entity_id',
                    Table::ACTION_CASCADE
                )->setComment('Blog Post To Product Linkage Table');
            $installer->getConnection()->createTable($table);
        }

        if (version_compare($context->getVersion(), '1.0.3') < 0) {
            $connection->dropTable($installer->getTable('mst_blog_store_post'));

            $table = $installer->getConnection()
                ->newTable($installer->getTable('mst_blog_store_post'))
                ->addColumn(
                    'store_id',
                    Table::TYPE_SMALLINT,
                    null,
                    ['unsigned' => true, 'nullable' => false, 'primary' => true, 'default' => '0'],
                    'Store ID'
                )->addColumn(
                    'post_id',
                    Table::TYPE_INTEGER,
                    null,
                    ['unsigned' => true, 'nullable' => false, 'primary' => true, 'default' => '0'],
                    'Post ID'
                )->addIndex(
                    $installer->getIdxName('mst_blog_store_post', ['post_id']),
                    ['post_id']
                )->addForeignKey(
                    $installer->getFkName(
                        'mst_blog_store_post',
                        'store_id',
                        'store',
                        'store_id'
                    ),
                    'store_id',
                    $installer->getTable('store'),
                    'store_id',
                    Table::ACTION_CASCADE
                )->addForeignKey(
                    $installer->getFkName(
                        'mst_blog_store_post',
                        'post_id',
                        'mst_blog_post_entity',
                        'entity_id'
                    ),
                    'post_id',
                    $installer->getTable('mst_blog_post_entity'),
                    'entity_id',
                    Table::ACTION_CASCADE
                )->setComment('Blog Post To Store Linkage Table');
            $installer->getConnection()->createTable($table);
        }
        if (version_compare($context->getVersion(), '1.0.4') < 0) {
            include_once 'Upgrade_1_0_4.php';

            Upgrade_1_0_4::upgrade($installer, $context);
        }
    }
}
