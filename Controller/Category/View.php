<?php

namespace Mirasvit\Blog\Controller\Category;

use Magento\Backend\Model\View\Result\Page;
use Magento\Framework\Controller\ResultFactory;
use Mirasvit\Blog\Controller\Category;

class View extends Category
{
    /**
     * @return Page
     */
    public function execute()
    {
        if ($this->initCategory()) {
            /* @var Page $resultPage */
            $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);

            return $resultPage;
        } else {
            $this->_forward('no_route');
        }
    }
}
