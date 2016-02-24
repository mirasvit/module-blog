<?php

namespace Mirasvit\Blog\Controller;

use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Customer\Model\Session;

abstract class Category extends Action
{
    public function __construct(
        \Mirasvit\Blog\Model\CategoryFactory $categoryFactory,
        \Magento\Catalog\Model\Session $session,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\App\Action\Context $context
    ) {
        $this->categoryFactory = $categoryFactory;
        $this->session = $session;

        $this->registry = $registry;
        $this->context = $context;
        $this->resultFactory = $context->getResultFactory();
        parent::__construct($context);
    }

    /**
     * @return \Mirasvit\Blog\Model\Category
     */
    protected function initCategory()
    {
        if ($id = $this->getRequest()->getParam('id')) {
            $post = $this->categoryFactory->create()->load($id);
            if ($post->getId() > 0) {
                $this->registry->register('current_blog_category', $post);

                return $post;
            }
        }
    }
}
