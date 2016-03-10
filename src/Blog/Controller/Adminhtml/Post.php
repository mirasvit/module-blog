<?php

namespace Mirasvit\Blog\Controller\Adminhtml;

use Magento\Backend\App\Action;
use Mirasvit\Blog\Model\PostFactory;
use Magento\Framework\Registry;
use Magento\Backend\App\Action\Context;
use Magento\Store\Model\StoreManagerInterface;

use Magento\Framework\Controller\Result\JsonFactory;

abstract class Post extends Action
{
    /**
     * @var PostFactory
     */
    protected $postFactory;

    /**
     * @var Context
     */
    protected $context;

    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var Registry
     */
    protected $registry;

    /**
     * @var JsonFactory
     */
    protected $jsonFactory;

    /**
     * @param PostFactory           $authorFactory
     * @param StoreManagerInterface $storeManager
     * @param JsonFactory           $jsonFactory
     * @param Registry              $registry
     * @param Context               $context
     */
    public function __construct(
        PostFactory $authorFactory,
        StoreManagerInterface $storeManager,
        JsonFactory $jsonFactory,
        Registry $registry,
        Context $context
    ) {
        $this->postFactory = $authorFactory;
        $this->storeManager = $storeManager;
        $this->jsonFactory = $jsonFactory;
        $this->registry = $registry;
        $this->context = $context;

        $this->resultFactory = $context->getResultFactory();

        parent::__construct($context);
    }

    /**
     * {@inheritdoc}
     * @param \Magento\Backend\Model\View\Result\Page\Interceptor $resultPage
     * @return \Magento\Backend\Model\View\Result\Page\Interceptor
     */
    protected function initPage($resultPage)
    {
        $resultPage->setActiveMenu('Mirasvit_Blog::blog');
        $resultPage->getConfig()->getTitle()->prepend(__('Mirasvit Blog'));
        $resultPage->getConfig()->getTitle()->prepend(__('Posts'));

        return $resultPage;
    }

    /**
     * @return \Mirasvit\Blog\Model\Post
     */
    public function initModel()
    {
        $model = $this->postFactory->create();
        if ($this->getRequest()->getParam('id')) {
            $model->load($this->getRequest()->getParam('id'));
        }

        $this->registry->register('current_model', $model);

        return $model;
    }

    /**
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->context->getAuthorization()->isAllowed('Mirasvit_Blog::blog_post');
    }
}
