<?php

namespace Mirasvit\Blog\Controller;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Registry;
use Mirasvit\Blog\Api\Data\CategoryInterface;
use Mirasvit\Blog\Model\CategoryFactory;

abstract class Category extends Action
{
    /**
     * @var CategoryFactory
     */
    protected $categoryFactory;

    /**
     * @var Registry
     */
    protected $registry;

    public function __construct(
        CategoryFactory $authorFactory,
        Registry $registry,
        Context $context
    ) {
        $this->categoryFactory = $authorFactory;
        $this->registry        = $registry;
        $this->context         = $context;
        $this->resultFactory   = $context->getResultFactory();

        parent::__construct($context);
    }

    /**
     * @return \Mirasvit\Blog\Model\Category
     */
    protected function initCategory()
    {
        if ($id = $this->getRequest()->getParam(CategoryInterface::ID)) {
            $post = $this->categoryFactory->create()->load($id);
            if ($post->getId() > 0) {
                $this->registry->register('current_blog_category', $post);

                return $post;
            }
        }
    }
}
