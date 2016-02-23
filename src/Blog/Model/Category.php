<?php

namespace Mirasvit\Blog\Model;

use Magento\Framework\Model\AbstractExtensibleModel;

/**
 * @method string getName()
 * @method string getContent()
 * @method string getUrlKey()
 * @method int getStatus()
 */
class Category extends AbstractExtensibleModel
{
    const ENTITY = 'blog_category';

    protected function _construct()
    {
        $this->_init('Mirasvit\Blog\Model\ResourceModel\Category');
    }
}