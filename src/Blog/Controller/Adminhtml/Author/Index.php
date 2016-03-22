<?php

namespace Mirasvit\Blog\Controller\Adminhtml\Author;

use Magento\Framework\Controller\ResultFactory;
use Mirasvit\Blog\Controller\Adminhtml\Author;

class Index extends Author
{
    /**
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {

        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->context->getResultFactory()->create(ResultFactory::TYPE_PAGE);

        $this->initPage($resultPage)
            ->getConfig()->getTitle()->prepend(__('Authors'));

        $this->_addContent($resultPage->getLayout()
            ->createBlock('Mirasvit\Blog\Block\Adminhtml\Author'));

        return $resultPage;
    }
}
