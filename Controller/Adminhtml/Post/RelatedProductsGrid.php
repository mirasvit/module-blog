<?php

namespace Mirasvit\Blog\Controller\Adminhtml\Post;

use Mirasvit\Blog\Controller\Adminhtml\Post;

class RelatedProductsGrid extends Post
{
    /**
     * @return void
     */
    public function execute()
    {
        $this->initModel();
        $this->_view->loadLayout()
            ->getLayout()
            ->getBlock('blog.post.tab.products')
            ->setProductsRelated($this->getRequest()->getPost('products_related'));
        $this->_view->renderLayout();
    }
}
