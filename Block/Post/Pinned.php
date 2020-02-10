<?php

namespace Mirasvit\Blog\Block\Post;

use Magento\Framework\Registry;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Mirasvit\Blog\Model\Category;
use Mirasvit\Blog\Model\Post;
use Mirasvit\Blog\Model\ResourceModel\Post\CollectionFactory as PostCollectionFactory;

class Pinned extends Template
{
    /**
     * @var PostCollectionFactory
     */
    protected $postCollectionFactory;

    /**
     * @var Registry
     */
    protected $registry;

    /**
     * @var Context
     */
    protected $context;

    /**
     * @param PostCollectionFactory $postCollectionFactory
     * @param Registry              $registry
     * @param Context               $context
     * @param array                 $data
     */
    public function __construct(
        PostCollectionFactory $postCollectionFactory,
        Registry $registry,
        Context $context,
        array $data = []
    ) {
        $this->postCollectionFactory = $postCollectionFactory;
        $this->registry              = $registry;
        $this->context               = $context;

        parent::__construct($context, $data);
    }

    /**
     * @return Post[]
     */
    public function getCollection()
    {
        $collection = $this->postCollectionFactory->create()
            ->addAttributeToSelect(['name', 'featured_image', 'url_key'])
            ->addVisibilityFilter()
            ->addStoreFilter($this->context->getStoreManager()->getStore()->getId())
            ->addAttributeToFilter('is_pinned', 1);

        if ($this->getCategory()) {
            $collection->addCategoryFilter($this->getCategory());
        }

        return $collection;
    }

    /**
     * @return Category|false
     */
    public function getCategory()
    {
        return $this->registry->registry('current_blog_category');
    }
}
