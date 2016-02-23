<?php
namespace Mirasvit\Blog\Model\ResourceModel;

class Post extends \Magento\Eav\Model\Entity\AbstractEntity
{
    public function getEntityType()
    {
        if (empty($this->_type)) {
            $this->setType(\MIrasvit\Blog\Model\Post::ENTITY);
        }
        return parent::getEntityType();
    }
}