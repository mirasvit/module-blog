<?php
namespace Mirasvit\Blog\Model\ResourceModel\Post;

class Collection extends \Magento\Eav\Model\Entity\Collection\AbstractCollection
{
    protected function _construct()
    {
        $this->_init('Mirasvit\Blog\Model\Post', 'Mirasvit\Blog\Model\ResourceModel\Post');
    }
}