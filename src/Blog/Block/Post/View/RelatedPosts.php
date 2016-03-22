<?php
namespace Mirasvit\Blog\Block\Post\View;

use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Magento\Framework\Registry;
use Mirasvit\Blog\Model\ResourceModel\Post\CollectionFactory as PostCollectionFactory;

class RelatedPosts extends Template
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
     * @param PostCollectionFactory $postCollectionFactory
     * @param Registry              $registry
     * @param Context               $context
     */
    public function __construct(
        PostCollectionFactory $postCollectionFactory,
        Registry $registry,
        Context $context
    ) {
        $this->postCollectionFactory = $postCollectionFactory;
        $this->registry = $registry;

        parent::__construct($context);
    }

    /**
     * @return \Mirasvit\Blog\Model\ResourceModel\Post\Collection
     */
    public function getPostCollection()
    {
        $tags = $this->getCurrentPost()->getTagIds();
        $collection = $this->postCollectionFactory->create()
            ->addTagFilter($tags)
            ->addFieldToFilter('entity_id', ['neq' => $this->getCurrentPost()->getId()])
            ->addVisibilityFilter()
            ->addAttributeToSelect('*');

        return $collection;
    }

    /**
     * @return \Mirasvit\Blog\Model\Post
     */
    public function getCurrentPost()
    {
        return $this->registry->registry('current_blog_post');
    }
}