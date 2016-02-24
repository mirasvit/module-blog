<?php

namespace Mirasvit\Blog\Controller\Category;

use Magento\Framework\Controller\ResultFactory;

class View extends \Mirasvit\Blog\Controller\Category
{
    /**
     * @return bool|\Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        if ($category = $this->initCategory()) {
            /* @var \Magento\Backend\Model\View\Result\Page $resultPage */
            $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);

            return $resultPage;
        } else {
            return $this->resultForwardFactory->create()->forward('noroute');
        }
    }
}
