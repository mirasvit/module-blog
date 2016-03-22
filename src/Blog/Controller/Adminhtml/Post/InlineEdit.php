<?php
namespace Mirasvit\Blog\Controller\Adminhtml\Post;

use Magento\Backend\App\Action\Context;
use Mirasvit\Blog\Controller\Adminhtml\Post;

class InlineEdit extends Post
{
    /**
     * {@inheritdoc}
     */
    public function execute()
    {
        /** @var \Magento\Framework\Controller\Result\Json $resultJson */
        $resultJson = $this->jsonFactory->create();

        $error = false;
        $messages = [];

        $postItems = $this->getRequest()->getParam('items', []);
        if (!($this->getRequest()->getParam('isAjax') && count($postItems))) {
            return $resultJson->setData([
                'messages' => [__('Please correct the data sent.')],
                'error'    => true,
            ]);
        }

        foreach (array_keys($postItems) as $postId) {
            /** @var \Magento\Cms\Model\Page $page */
            $post = $this->postFactory->create()->load($postId);

            try {
                $data = $postItems[$postId];

                $post->addData($data)
                    ->save();

            } catch (\Exception $e) {
                $messages[] = __('Something went wrong while saving the post.');
                $error = true;
            }
        }

        return $resultJson->setData([
            'messages' => $messages,
            'error'    => $error
        ]);
    }
}
