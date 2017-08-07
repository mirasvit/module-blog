<?php

namespace Mirasvit\Blog\Controller\Adminhtml\Post;

use Mirasvit\Blog\Controller\Adminhtml\Post;

/**
 * @SuppressWarnings(PHPMD.CyclomaticComplexity)
 * @SuppressWarnings(PHPMD.NPathComplexity)
 */
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

            $data = $this->filterPostData($data);

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

    /**
     * @param array $data
     * @return array
     */
    protected function filterPostData($data)
    {
        $result = $data['post'];

        if (!isset($result['is_pinned'])) {
            $result['is_pinned'] = false;
        }
        if (isset($result['store_ids'])) {
            $result['store_ids'] = explode(',', $result['store_ids']);
        }
        $formatter = new \IntlDateFormatter(
            $this->context->getLocaleResolver()->getLocale(),
            \IntlDateFormatter::MEDIUM,
            \IntlDateFormatter::SHORT,
            null,
            null,
            'MMM. d, y h:mm a'
        );
        if (isset($result['created_at'])) {
            $result['created_at'] = $formatter->parse($result['created_at']);
        } else {
            $result['created_at'] = $formatter->parse(date('Y-m-d h:i:s'));
        }

        if (isset($data['featured_image'])
            && is_array($data['featured_image'])
            && isset($data['featured_image']['delete'])) {
            $result['featured_image'] = '';
        }

        if (isset($data['links'])) {
            $links = is_array($data['links']) ? $data['links'] : [];
            foreach (['relatedproducts' => 'product_ids'] as $type => $alias) {
                if (isset($links[$type])) {
                    $result[$alias] = array_keys($this->jsHelper->decodeGridSerializedInput($links[$type]));
                }
            }
        }

        return $result;
    }
}
