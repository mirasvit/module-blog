<?php
namespace Mirasvit\Blog\Block\Post\View;

use Magento\Framework\View\Element\Template;
use Magento\Catalog\Block\Product\Context;
use Magento\Framework\Registry;
use Mirasvit\Blog\Model\ResourceModel\Post\CollectionFactory as PostCollectionFactory;

class RelatedProducts extends \Magento\Catalog\Block\Product\AbstractProduct
{
    /**
     * @var Registry
     */
    protected $registry;

    /**
     * @param Registry $registry
     * @param Context  $context
     */
    public function __construct(
        Registry $registry,
        Context $context
    ) {
        $this->registry = $registry;

        parent::__construct($context);
    }

    /**
     * @return \Magento\Catalog\Model\ResourceModel\Product\Collection
     */
    public function getPostCollection()
    {
        return $this->getCurrentPost()->getRelatedProducts();
    }

    /**
     * @return \Mirasvit\Blog\Model\Post
     */
    public function getCurrentPost()
    {
        return $this->registry->registry('current_blog_post');
    }
}