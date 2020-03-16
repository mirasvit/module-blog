<?php

namespace Mirasvit\Blog\Block\Post\View;

use Magento\Catalog\Block\Product\AbstractProduct;
use Magento\Catalog\Block\Product\Context;
use Magento\Catalog\Model\ResourceModel\Product\Collection;
use Magento\Framework\Registry;
use Mirasvit\Blog\Model\Post;

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
     * @return Collection
     */
    public function getRelatedProducts()
    {
        return $this->getCurrentPost()->getRelatedProducts();
    }

    /**
     * @return Post
     */
    public function getCurrentPost()
    {
        return $this->registry->registry('current_blog_post');
    }
}
