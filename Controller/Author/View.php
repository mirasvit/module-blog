<?php

namespace Mirasvit\Blog\Controller\Author;

use Magento\Backend\Model\View\Result\Page;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Registry;
use Mirasvit\Blog\Model\Author;
use Mirasvit\Blog\Model\AuthorFactory;

class View extends Action
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
     * @var ResultFactory
     */
    protected $resultFactory;

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
        $this->registry      = $registry;
        $this->resultFactory = $context->getResultFactory();

        parent::__construct($context);
    }

    /**
     * @return Page
     */
    public function execute()
    {
        if ($this->initModel()) {
            /* @var Page $resultPage */
            $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);

            return $resultPage;
        } else {
            $this->_forward('no_route');
        }
    }

    /**
     * @return Author
     */
    protected function initModel()
    {
        if ($id = $this->getRequest()->getParam('id')) {
            $author = $this->authorFactory->create()->load($id);
            if ($author->getId() > 0) {
                $this->registry->register('current_blog_author', $author);

                return $author;
            }
        }

        return false;
    }
}
