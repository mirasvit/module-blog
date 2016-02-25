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
        $resultRedirect = $this->resultRedirectFactory->create();

        if ($data = $this->getRequest()->getParams()) {
            $model = $this->initModel();

            if (!isset($data['is_pinned'])) {
                $data['is_pinned'] = false;
            }

            if (is_array($data['featured_image'])) {
                unset($data['featured_image']);
            }

            $model->addData($data);

            try {
                if (isset($data['preview']) && $data['preview']) {
                    $revision = $model->saveAsRevision();

                    //@todo: Improve
                    $url = $this->storeManager->getStore()->getBaseUrl();
                    $url .= 'blog/post/view/id/' . $revision->getId() . '/';
                    return $resultRedirect->setUrl($url);
                } else {
                    $model->save();
                }
                $this->messageManager->addSuccess(__('Post was successfully saved'));
                $this->context->getSession()->setFormData(false);

                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['id' => $model->getId()]);
                }

                return $this->context->getResultRedirectFactory()->create()->setPath('*/*/');
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage($e->getMessage());

                return $resultRedirect->setPath('*/*/edit', ['id' => $this->getRequest()->getParam('id')]);
            }
        } else {
            $resultRedirect->setPath('*/*/');
            $this->messageManager->addError('No data to save.');

            return $resultRedirect;
        }
    }
}
