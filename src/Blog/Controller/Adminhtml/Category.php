<?php

namespace Mirasvit\Blog\Controller\Adminhtml;

use Magento\Backend\App\Action;
use Mirasvit\Blog\Model\CategoryFactory;
use Magento\Framework\Registry;
use Magento\Backend\App\Action\Context;

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

    /**
     * @var Context
     */
    protected $context;

    /**
     * @param CategoryFactory $authorFactory
     * @param Registry        $registry
     * @param Context         $context
     */
    public function __construct(
        CategoryFactory $authorFactory,
        Registry $registry,
        Context $context
    ) {
        $this->categoryFactory = $authorFactory;
        $this->registry = $registry;
        $this->context = $context;

        parent::__construct($context);
    }

    /**
     * {@inheritdoc}
     * @param \Magento\Backend\Model\View\Result\Page $resultPage
     * @return \Magento\Backend\Model\View\Result\Page\Interceptor
     */
    protected function initPage($resultPage)
    {
        $resultPage->setActiveMenu('Mirasvit_Blog::blog');
        $resultPage->getConfig()->getTitle()->prepend(__('Mirasvit Blog MX'));
        $resultPage->getConfig()->getTitle()->prepend(__('Categories'));

        return $resultPage;
    }

    /**
     * @return \Mirasvit\Blog\Model\Category
     */
    public function initModel()
    {
        $model = $this->categoryFactory->create();
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
        return $this->context->getAuthorization()->isAllowed('Mirasvit_Blog::blog_category');
    }
}
