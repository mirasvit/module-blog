<?php

namespace Mirasvit\Blog\Controller\Adminhtml\Post;

use Magento\Backend\Model\View\Result\Page;
use Magento\Backend\Model\View\Result\Page\Interceptor;
use Magento\Framework\Controller\ResultFactory;
use Mirasvit\Blog\Api\Data\PostInterface;
use Mirasvit\Blog\Controller\Adminhtml\Post;

class Edit extends Post
{
    /**
     * @return Page
     */
    public function execute()
    {
        /** @var Interceptor $resultPage */
        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);

        $id    = $this->getRequest()->getParam(PostInterface::ID);
        $model = $this->initModel();

        if ($id && !is_array($id) && !$model->getId()) {
            $this->messageManager->addErrorMessage(__('This post no longer exists.'));

            return $this->resultRedirectFactory->create()->setPath('*/*/');
        }

        $this->initPage($resultPage)->getConfig()->getTitle()->prepend(
            $model->getName() ? $model->getName() : __('New Post')
        );

        return $resultPage;
    }
}
