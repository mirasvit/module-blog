<?php

namespace Mirasvit\Blog\Controller\Adminhtml\Post;

use Mirasvit\Blog\Controller\Adminhtml\Post;

class Delete extends Post
{
    /**
     * {@inheritdoc}
     */
    public function execute()
    {
        $model = $this->initModel();

        $resultRedirect = $this->resultRedirectFactory->create();

        if ($model->getId()) {
            try {
                $model->delete();

                $this->messageManager->addSuccess(__('The post has been deleted.'));

                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                $this->messageManager->addError($e->getMessage());
                return $resultRedirect->setPath('*/*/edit', ['id' => $model->getId()]);
            }
        } else {
            $this->messageManager->addError(__('This post no longer exists.'));

            return $resultRedirect->setPath('*/*/');
        }
    }
}
