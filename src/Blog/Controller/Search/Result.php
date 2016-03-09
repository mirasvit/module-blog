<?php

namespace Mirasvit\Blog\Controller\Search;

use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\App\Action\Action;

class Result extends Action
{
    /**
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);

        return $resultPage;
    }
}
