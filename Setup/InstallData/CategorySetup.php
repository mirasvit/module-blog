<?php

namespace Mirasvit\Blog\Setup\InstallData;

use Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface;
use Magento\Eav\Model\Entity\Setup\Context;
use Magento\Eav\Model\ResourceModel\Entity\Attribute\Group\CollectionFactory;
use Magento\Eav\Setup\EavSetup;
use Magento\Framework\App\CacheInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Mirasvit\Blog\Model\CategoryFactory;

class CategorySetup extends EavSetup
{
    /**
     * Category model factory
     * @var CategoryFactory
     */
    private $categoryFactory;

    /**
     * @param ModuleDataSetupInterface $setup
     * @param Context                  $context
     * @param CacheInterface           $cache
     * @param CollectionFactory        $attrGroupCollectionFactory
     * @param CategoryFactory          $categoryFactory
     */
    public function __construct(
        ModuleDataSetupInterface $setup,
        Context $context,
        CacheInterface $cache,
        CollectionFactory $attrGroupCollectionFactory,
        CategoryFactory $categoryFactory
    ) {
        $this->categoryFactory = $categoryFactory;
        parent::__construct($setup, $context, $cache, $attrGroupCollectionFactory);
    }

    /**
     * Default entities and attributes
     * @return array
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function getDefaultEntities()
    {
        return [
            'blog_category' => [
                'entity_model' => 'Mirasvit\Blog\Model\ResourceModel\Category',
                'table'        => 'mst_blog_category_entity',
                'attributes'   => [
                    'name' => [
                        'type'   => 'varchar',
                        'label'  => 'Name',
                        'input'  => 'text',
                        'global' => ScopedAttributeInterface::SCOPE_STORE,
                    ],

                    'url_key' => [
                        'type'   => 'varchar',
                        'label'  => 'Url Key',
                        'input'  => 'text',
                        'global' => ScopedAttributeInterface::SCOPE_STORE,
                    ],

                    'status' => [
                        'type'   => 'int',
                        'label'  => 'Status',
                        'input'  => 'select',
                        'source' => 'Magento\Eav\Model\Entity\Attribute\Source\Boolean',
                        'global' => ScopedAttributeInterface::SCOPE_WEBSITE,
                    ],

                    'content' => [
                        'type'   => 'text',
                        'label'  => 'Content',
                        'input'  => 'textarea',
                        'global' => ScopedAttributeInterface::SCOPE_STORE,
                    ],

                    'meta_title'       => [
                        'type'   => 'varchar',
                        'label'  => 'Meta Title',
                        'input'  => 'text',
                        'global' => ScopedAttributeInterface::SCOPE_STORE,
                    ],
                    'meta_keywords'    => [
                        'type'   => 'text',
                        'label'  => 'Meta Keywords',
                        'input'  => 'textarea',
                        'global' => ScopedAttributeInterface::SCOPE_STORE,
                    ],
                    'meta_description' => [
                        'type'   => 'varchar',
                        'label'  => 'Meta Description',
                        'input'  => 'textarea',
                        'global' => ScopedAttributeInterface::SCOPE_STORE,
                    ],
                ],
            ],
        ];
    }

    /**
     * @return CategoryFactory
     */
    public function getCategoryFactory()
    {
        return $this->categoryFactory;
    }
}
