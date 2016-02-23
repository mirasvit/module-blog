<?php
namespace Mirasvit\Blog\Model\ResourceModel;

class Category extends \Magento\Eav\Model\Entity\AbstractEntity
{
    public function getEntityType()
    {
        if (empty($this->_type)) {
            $this->setType(\MIrasvit\Blog\Model\Category::ENTITY);
        }
        return parent::getEntityType();
    }
}