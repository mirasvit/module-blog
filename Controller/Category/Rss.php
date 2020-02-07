<?php

namespace Mirasvit\Blog\Controller\Category;

use Mirasvit\Blog\Controller\Category;

class Rss extends Category
{
    /**
     * @return void
     */
    public function execute()
    {
        $rss = $this->_view->getLayout()->createBlock('Mirasvit\Blog\Block\Category\Rss')
            ->setTemplate('category/rss.phtml')
            ->toHtml();

        $this->getResponse()
            ->setHeader('Content-type', 'text/xml')
            ->setBody($rss);
    }
}
