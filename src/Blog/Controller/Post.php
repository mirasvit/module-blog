<?php

namespace Mirasvit\Blog\Controller;

use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Customer\Model\Session;


abstract class Post extends Action
{
    public function __construct(
        \Mirasvit\Blog\Model\PostFactory $categoryFactory,
        \Mirasvit\Blog\Model\ResourceModel\Post\CollectionFactory $postCollectionFactory,
        \Magento\Catalog\Model\Session $session,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\App\Action\Context $context
    ) {
        $this->postFactory = $categoryFactory;
        $this->postCollectionFactory = $postCollectionFactory;
        $this->session = $session;

        $this->registry = $registry;
        $this->context = $context;
        $this->resultFactory = $context->getResultFactory();
        parent::__construct($context);
    }

    /**
     * @return \Magento\Catalog\Model\Session
     */
    protected function _getSession()
    {
        return $this->session;
    }

    /**
     * @return \Mirasvit\Blog\Model\Post
     */
    protected function initPost()
    {
        if ($id = $this->getRequest()->getParam('id')) {
            $post = $this->postFactory->create()->load($id);
            if ($post->getId() > 0) {
                $this->registry->register('current_post', $post);

                return $post;
            }
        }
    }
}
