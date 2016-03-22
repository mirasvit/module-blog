<?php

namespace Mirasvit\Blog\Controller\Adminhtml\Category;

use Mirasvit\Blog\Controller\Adminhtml\Category;

class NewAction extends Category
{
    /**
     * {@inheritdoc}
     */
    public function execute()
    {
        return $this->resultRedirectFactory->create()->setPath('*/*/edit');
    }
}
