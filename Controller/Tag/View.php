<?php

namespace Mirasvit\Blog\Controller\Tag;

use Magento\Backend\Model\View\Result\Page;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Registry;
use Mirasvit\Blog\Model\Tag;
use Mirasvit\Blog\Model\TagFactory;

class View extends Action
{
    /**
     * @var TagFactory
     */
    protected $tagFactory;

    /**
     * @var Registry
     */
    protected $registry;

    /**
     * @var ResultFactory
     */
    protected $resultFactory;

    /**
     * @param TagFactory $authorFactory
     * @param Registry   $registry
     * @param Context    $context
     */
    public function __construct(
        TagFactory $authorFactory,
        Registry $registry,
        Context $context
    ) {
        $this->tagFactory    = $authorFactory;
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
     * @return Tag
     */
    protected function initModel()
    {
        if ($id = $this->getRequest()->getParam('id')) {
            $tag = $this->tagFactory->create()->load($id);
            if ($tag->getId() > 0) {
                $this->registry->register('current_blog_tag', $tag);

                return $tag;
            }
        }

        return false;
    }
}
