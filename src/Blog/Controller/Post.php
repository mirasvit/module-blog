<?php

namespace Mirasvit\Blog\Controller;

use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Customer\Model\Session;
use Mirasvit\Blog\Model\PostFactory;
use Magento\Framework\Registry;

abstract class Post extends Action
{
    /**
     * @var PostFactory
     */
    protected $postFactory;

    /**
     * @var Registry
     */
    protected $registry;

    /**
     * @param PostFactory $postFactory
     * @param Registry    $registry
     * @param Context     $context
     */
    public function __construct(
        PostFactory $postFactory,
        Registry $registry,
        Context $context
    ) {
        $this->postFactory = $postFactory;
        $this->registry = $registry;
        $this->context = $context;
        $this->resultFactory = $context->getResultFactory();

        parent::__construct($context);
    }

    /**
     * @return \Mirasvit\Blog\Model\Post
     */
    protected function initModel()
    {
        if ($id = $this->getRequest()->getParam('id')) {
            $post = $this->postFactory->create()->load($id);
            if ($post->getId() > 0) {
                $this->registry->register('current_blog_post', $post);

                return $post;
            }
        }
    }
}
