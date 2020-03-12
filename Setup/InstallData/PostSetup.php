<?php

namespace Mirasvit\Blog\Setup\InstallData;

use Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface;
use Magento\Eav\Model\Entity\Setup\Context;
use Magento\Eav\Model\ResourceModel\Entity\Attribute\Group\CollectionFactory;
use Magento\Eav\Setup\EavSetup;
use Magento\Framework\App\CacheInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Mirasvit\Blog\Model\PostFactory;

class PostSetup extends EavSetup
{
    /**
     * Category model factory
     * @var PostFactory
     */
    private $postFactory;

    /**
     * Init
     *
     * @param ModuleDataSetupInterface $setup
     * @param Context                  $context
     * @param CacheInterface           $cache
     * @param CollectionFactory        $attrGroupCollectionFactory
     * @param PostFactory              $categoryFactory
     */
    public function __construct(
        ModuleDataSetupInterface $setup,
        Context $context,
        CacheInterface $cache,
        CollectionFactory $attrGroupCollectionFactory,
        PostFactory $categoryFactory
    ) {
        $this->postFactory = $categoryFactory;
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
            'blog_post' => [
                'entity_model' => 'Mirasvit\Blog\Model\ResourceModel\Post',
                'table'        => 'mst_blog_post_entity',
                'attributes'   => [
                    'author_id' => [
                        'type'   => 'static',
                        'label'  => 'Author Id',
                        'input'  => 'select',
                        'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
                    ],
                    'type'      => [
                        'type'   => 'static',
                        'label'  => 'Type',
                        'input'  => 'text',
                        'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
                    ],
                    'name'      => [
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
                        'source' => 'Mirasvit\Blog\Model\Post\Attribute\Source\Status',
                        'global' => ScopedAttributeInterface::SCOPE_WEBSITE,
                    ],

                    'content' => [
                        'type'   => 'text',
                        'label'  => 'Content',
                        'input'  => 'textarea',
                        'global' => ScopedAttributeInterface::SCOPE_STORE,
                    ],

                    'short_content' => [
                        'type'   => 'text',
                        'label'  => 'Short Content',
                        'input'  => 'textarea',
                        'global' => ScopedAttributeInterface::SCOPE_STORE,
                    ],

                    'featured_image' => [
                        'type'   => 'text',
                        'label'  => 'Featured Image',
                        'input'  => 'text',
                        'global' => ScopedAttributeInterface::SCOPE_STORE,
                    ],

                    'is_pinned' => [
                        'type'   => 'int',
                        'label'  => 'Is pinned?',
                        'input'  => 'text',
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
}
