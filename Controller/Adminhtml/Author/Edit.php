<?php

namespace Mirasvit\Blog\Controller\Adminhtml\Author;

use Magento\Backend\Model\View\Result\Page;
use Magento\Backend\Model\View\Result\Page\Interceptor;
use Magento\Framework\Controller\ResultFactory;
use Mirasvit\Blog\Controller\Adminhtml\Author;

class Edit extends Author
{
    /**
     * @return Page
     */
    public function execute()
    {
        /** @var Interceptor $resultPage */
        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);

        $id    = $this->getRequest()->getParam('id');
        $model = $this->initModel();

        if ($id && !$model->getId()) {
            $this->messageManager->addError(__('This author no longer exists.'));

            return $this->resultRedirectFactory->create()->setPath('*/*/');
        }

        $this->initPage($resultPage)->getConfig()->getTitle()->prepend($model->getName());

        return $resultPage;
    }
}
