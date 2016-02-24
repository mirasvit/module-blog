<?php

namespace Mirasvit\Blog\Controller\Adminhtml\Category;

use Magento\Framework\Controller\ResultFactory;
use Mirasvit\Blog\Controller\Adminhtml\Category;

class Index extends Category
{
    /**
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {

        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->context->getResultFactory()->create(ResultFactory::TYPE_PAGE);

        $this->initPage($resultPage)
            ->getConfig()->getTitle()->prepend(__('Categories'));

        $this->_addContent($resultPage->getLayout()
            ->createBlock('\Mirasvit\Blog\Block\Adminhtml\Category'));

        return $resultPage;
    }
}
