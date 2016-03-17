<?php

namespace Mirasvit\Blog\Controller\Adminhtml;

use Magento\Backend\App\Action;
use Mirasvit\Blog\Model\AuthorFactory;
use Magento\Framework\Registry;
use Magento\Backend\App\Action\Context;

abstract class Author extends Action
{
    /**
     * @var AuthorFactory
     */
    protected $authorFactory;

    /**
     * @var Registry
     */
    protected $registry;

    /**
     * @var Context
     */
    protected $context;

    /**
     * @param AuthorFactory $authorFactory
     * @param Registry      $registry
     * @param Context       $context
     */
    public function __construct(
        AuthorFactory $authorFactory,
        Registry $registry,
        Context $context
    ) {
        $this->authorFactory = $authorFactory;
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
        $resultPage->getConfig()->getTitle()->prepend(__('Authors'));

        return $resultPage;
    }

    /**
     * @return \Mirasvit\Blog\Model\Author
     */
    public function initModel()
    {
        $model = $this->authorFactory->create();
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
        return $this->context->getAuthorization()->isAllowed('Mirasvit_Blog::blog_author');
    }
}
