<?php

namespace Mirasvit\Blog\Block\Sidebar;

use Magento\Framework\Registry;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Magento\Widget\Block\BlockInterface;
use Mirasvit\Blog\Model\Category;
use Mirasvit\Blog\Model\Post;
use Mirasvit\Blog\Model\ResourceModel\Post\CollectionFactory as PostCollectionFactory;

class Recent extends Template implements BlockInterface
{
    /**
     * @var string
     */
    protected $_template = 'Mirasvit_Blog::sidebar/recent.phtml';

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
        return $this->postCollectionFactory->create()
            ->addVisibilityFilter()
            ->addStoreFilter($this->context->getStoreManager()->getStore()->getId())
            ->addAttributeToSelect(['name', 'featured_image', 'url_key'])
            ->setOrder('created_at', 'desc');
    }

    /**
     * @param Category $category
     *
     * @return bool
     */
    public function isCurrent($category)
    {
        if ($this->getCurrentCategory() && $this->getCurrentCategory()->getId() == $category->getId()) {
            return true;
        }

        return false;
    }

    /**
     * @return Category|false
     */
    public function getCurrentCategory()
    {
        return $this->registry->registry('current_blog_category');
    }

    /**
     * @return int
     */
    public function getPageSize()
    {
        if ($this->getData('page_size')) {
            return (int)$this->getData('page_size');
        }

        return 5;
    }

    /**
     * @return int
     */
    public function getImageWidth()
    {
        if ($this->getData('image_width')) {
            return (int)$this->getData('image_width');
        }

        return 0;
    }

    /**
     * @return int
     */
    public function getImageHeight()
    {
        if ($this->getData('image_height')) {
            return (int)$this->getData('image_height');
        }

        return 0;
    }
}
