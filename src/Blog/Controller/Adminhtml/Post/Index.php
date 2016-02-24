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
        $emptyPosts = $this->postFactory->create()->getCollection()
            ->addAttributeToFilter('name', ['eq' => '']);

        foreach ($emptyPosts as $post) {
            $post->delete();
        }

        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);

        $this->initPage($resultPage)
            ->getConfig()->getTitle()->prepend(__('All Posts'));

        $this->_addContent($resultPage->getLayout()
            ->createBlock('\Mirasvit\Blog\Block\Adminhtml\Post'));

        return $resultPage;
    }
}
