<?php

namespace Mirasvit\Blog\Controller\Adminhtml\Category;

use Magento\Backend\Model\View\Result\Page;
use Magento\Backend\Model\View\Result\Page\Interceptor;
use Magento\Framework\Controller\ResultFactory;
use Mirasvit\Blog\Controller\Adminhtml\Category;

class Edit extends Category
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
            $this->messageManager->addError(__('This category no longer exists.'));

            return $this->resultRedirectFactory->create()->setPath('*/*/');
        }

        $this->initPage($resultPage)->getConfig()->getTitle()->prepend($id ? $model->getName() : __('New Category'));

        return $resultPage;
    }
}
