<?php

namespace Mirasvit\Blog\Model;

use Magento\Framework\Model\AbstractExtensibleModel;

/**
 * @method string getName()
 * @method string getShortContent()
 * @method string getContent()
 * @method string getUrlKey()
 * @method int getStatus()
 */
class Post extends AbstractExtensibleModel
{
    const ENTITY = 'blog_post';

    protected function _construct()
    {
        $this->_init('Mirasvit\Blog\Model\ResourceModel\Post');
    }
}