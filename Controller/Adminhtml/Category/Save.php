<?php

namespace Mirasvit\Blog\Controller\Adminhtml\Category;

use Exception;
use Mirasvit\Blog\Controller\Adminhtml\Category;

class Save extends Category
{
    /**
     * {@inheritdoc}
     */
    public function execute()
    {
        $id             = $this->getRequest()->getParam('id');
        $resultRedirect = $this->resultRedirectFactory->create();

        if ($data = $this->getRequest()->getParams()) {
            $model = $this->initModel();

            if (!$model->getId() && $id) {
                $this->messageManager->addErrorMessage(__('This category no longer exists.'));

                return $resultRedirect->setPath('*/*/');
            }

            $model->addData($data);

            try {
                $model->save();
                $this->messageManager->addSuccessMessage(__('Category was successfully saved'));
                $this->context->getSession()->setFormData(false);

                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['id' => $model->getId()]);
                }

                return $this->context->getResultRedirectFactory()->create()->setPath('*/*/');
            } catch (Exception $e) {
                $this->messageManager->addErrorMessage($e->getMessage());

                return $resultRedirect->setPath('*/*/edit', ['id' => $this->getRequest()->getParam('id')]);
            }
        } else {
            $resultRedirect->setPath('*/*/');
            $this->messageManager->addErrorMessage('No data to save.');

            return $resultRedirect;
        }
    }
}
