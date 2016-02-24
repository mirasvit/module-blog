<?php

namespace Mirasvit\Blog\Controller\Post;

use Magento\Framework\Controller\ResultFactory;
use Mirasvit\Blog\Controller\Post;

class View extends Post
{
    /**
     * @return bool|\Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        if ($post = $this->initModel()) {
            /* @var \Magento\Backend\Model\View\Result\Page $resultPage */
            $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);

            return $resultPage;
        } else {
            return $this->resultForwardFactory->create()->forward('noroute');
        }
    }
}
