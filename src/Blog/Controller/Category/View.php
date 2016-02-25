<?php

namespace Mirasvit\Blog\Controller\Category;

use Magento\Framework\Controller\ResultFactory;

class View extends \Mirasvit\Blog\Controller\Category
{
    /**
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        if ($this->initCategory()) {
            /* @var \Magento\Backend\Model\View\Result\Page $resultPage */
            $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);

            return $resultPage;
        } else {
            $this->_forward('no_route');
        }
    }
}
