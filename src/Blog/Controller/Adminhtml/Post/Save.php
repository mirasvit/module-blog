<?php

namespace Mirasvit\Blog\Controller\Adminhtml\Post;

use Mirasvit\Blog\Controller\Adminhtml\Post;

class Save extends Post
{
    /**
     * {@inheritdoc}
     */
    public function execute()
    {
        if ($data = $this->getRequest()->getParams()) {
            $model = $this->initModel();


            $model->addData($data);

            try {
                $model->save();
                $this->messageManager->addSuccess(__('Post was successfully saved'));
                $this->backendSession->setFormData(false);

                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', ['id' => $model->getId()]);
                    return;
                }

                $this->_redirect('*/*/');
                return
            } catch (\Exception $e) {
                $this->messageManager->addError($e->getMessage());
                $this->backendSession->setFormData($data);
                $this->_redirect('*/*/edit', ['id' => $this->getRequest()->getParam('id')]);

                return;
            }
        }
        $this->messageManager->addError(__('Unable to find post to save.'));
        $this->_redirect('*/*/');
    }
}
