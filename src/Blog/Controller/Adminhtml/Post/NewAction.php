<?php

namespace Mirasvit\Blog\Controller\Adminhtml\Post;

use Mirasvit\Blog\Controller\Adminhtml\Post;

class NewAction extends Post
{
    /**
     * {@inheritdoc}
     */
    public function execute()
    {
        $post = $this->initModel()
            ->setName('')
            ->save();

        return $this->resultRedirectFactory->create()->setPath('*/*/edit', ['id' => $post->getId()]);
    }
}
