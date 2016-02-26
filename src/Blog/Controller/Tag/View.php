<?php

namespace Mirasvit\Blog\Controller\Tag;

use Magento\Framework\Controller\ResultFactory;
use Mirasvit\Blog\Controller\Tag;

class View extends Tag
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
