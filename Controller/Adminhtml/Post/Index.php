<?php

namespace Mirasvit\Blog\Controller\Adminhtml\Post;

use Magento\Backend\Model\View\Result\Page;
use Magento\Framework\Controller\ResultFactory;
use Mirasvit\Blog\Controller\Adminhtml\Post;

class Index extends Post
{
    /**
     * @return Page
     */
    public function execute()
    {
        /** @var Page $resultPage */
        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);

        $this->initPage($resultPage)
            ->getConfig()
            ->getTitle()
            ->prepend(__('All Posts'));

        return $resultPage;
    }
}
