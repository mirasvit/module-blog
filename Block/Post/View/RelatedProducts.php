<?php
namespace Mirasvit\Blog\Block\Post\View;

use Magento\Framework\View\Element\Template;
use Magento\Catalog\Block\Product\Context;
use Magento\Framework\Registry;
use Magento\Catalog\Block\Product\AbstractProduct;

class RelatedProducts extends AbstractProduct
{
    /**
     * @var Registry
     */
    protected $registry;

    /**
     * @param Context $context
     */
    public function __construct(
        Context $context
    ) {
        $this->registry = $context->getRegistry();

        parent::__construct($context);
    }

    /**
     * @return \Magento\Catalog\Model\ResourceModel\Product\Collection
     */
    public function getRelatedProducts()
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