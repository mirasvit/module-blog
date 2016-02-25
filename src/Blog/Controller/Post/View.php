<?php

namespace Mirasvit\Blog\Controller\Post;

use Magento\Framework\Controller\ResultFactory;
use Mirasvit\Blog\Controller\Post;

class View extends Post
{
    /**
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        if ($this->initModel()) {
            /* @var \Magento\Backend\Model\View\Result\Page $resultPage */
            $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);

            return $resultPage;
        } else {
            $this->_forward('no_route');
        }
    }
}
