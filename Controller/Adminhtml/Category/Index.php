<?php

namespace Mirasvit\Blog\Controller\Adminhtml\Category;

use Magento\Backend\Model\View\Result\Page;
use Magento\Framework\Controller\ResultFactory;
use Mirasvit\Blog\Controller\Adminhtml\Category;

class Index extends Category
{
    /**
     * @return Page
     */
    public function execute()
    {

        /** @var Page $resultPage */
        $resultPage = $this->context->getResultFactory()->create(ResultFactory::TYPE_PAGE);

        $this->initPage($resultPage)
            ->getConfig()->getTitle()->prepend(__('Categories'));

        $this->_addContent($resultPage->getLayout()
            ->createBlock('\Mirasvit\Blog\Block\Adminhtml\Category'));

        return $resultPage;
    }
}
