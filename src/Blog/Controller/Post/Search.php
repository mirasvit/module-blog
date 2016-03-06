<?php

namespace Mirasvit\Blog\Controller\Post;

use Magento\Framework\Controller\ResultFactory;
use Mirasvit\Blog\Controller\Post;

class Search extends Post
{
    /**
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);

        if ($query = $this->getRequest()->getParam('q')) {
            $this->registry->register('current_blog_query', $query);
        }

        return $resultPage;
    }
}
