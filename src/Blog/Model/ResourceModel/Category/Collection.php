<?php
namespace Mirasvit\Blog\Model\ResourceModel\Category;

class Collection extends \Magento\Eav\Model\Entity\Collection\AbstractCollection
{
    protected function _construct()
    {
        $this->_init('Mirasvit\Blog\Model\Category', 'Mirasvit\Blog\Model\ResourceModel\Category');
    }
}