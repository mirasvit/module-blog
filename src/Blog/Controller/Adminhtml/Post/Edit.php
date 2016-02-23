<?php

namespace Mirasvit\Blog\Controller\Adminhtml\Post;

use Magento\Framework\Controller\ResultFactory;
use Mirasvit\Blog\Controller\Adminhtml\Post;

class Edit extends Post
{
    /**
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);

        $model = $this->initModel();

        if ($model->getId()) {
            $this->initPage($resultPage)
                ->getConfig()->getTitle()->prepend($model->getName());

            return $resultPage;
        } else {
            $this->messageManager->addError(__('This post no longer exists.'));
            $this->_redirect('*/*/');
        }
    }
}
