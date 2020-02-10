<?php

namespace Mirasvit\Blog\Controller\Adminhtml\Category;

use Exception;
use Mirasvit\Blog\Controller\Adminhtml\Category;

class Delete extends Category
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

                $this->messageManager->addSuccess(__('The category has been deleted.'));

                return $resultRedirect->setPath('*/*/');
            } catch (Exception $e) {
                $this->messageManager->addError($e->getMessage());

                return $resultRedirect->setPath('*/*/edit', ['id' => $model->getId()]);
            }
        } else {
            $this->messageManager->addError(__('This category no longer exists.'));

            return $resultRedirect->setPath('*/*/');
        }
    }
}
