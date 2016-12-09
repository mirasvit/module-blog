<?php

namespace Mirasvit\Blog\Controller\Adminhtml\Post;

use Magento\Framework\Controller\ResultFactory;
use Mirasvit\Blog\Controller\Adminhtml\Post;

class Index extends Post
{
    /**
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);

        $this->initPage($resultPage)
             ->getConfig()
             ->getTitle()
             ->prepend(__('All Posts'));

        return $resultPage;
    }
}
