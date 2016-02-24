<?php
namespace Mirasvit\Blog\Model\ResourceModel\Post;

use Magento\Eav\Model\Entity\Collection\AbstractCollection;
use Mirasvit\Blog\Model\Post;
use Mirasvit\Blog\Model\Post\Attribute\Source\Status;

class Collection extends AbstractCollection
{
    protected function _construct()
    {
        $this->_init('Mirasvit\Blog\Model\Post', 'Mirasvit\Blog\Model\ResourceModel\Post');
    }

    /**
     * @return $this
     */
    public function addVisibilityFilter()
    {
        $this->addAttributeToFilter('status', Status::STATUS_PUBLISHED);

        return $this;
    }

    /**
     * @param \Mirasvit\Blog\Model\Category $category
     * @return $this
     */
    public function addCategoryFilter($category)
    {
        $this->getSelect()
            ->where("EXISTS (SELECT * FROM `{$this->getTable('mst_blog_category_post')}`
                AS `category_post`
                WHERE e.entity_id = category_post.post_id
                AND category_post.category_id in (?))", [0, $category->getId()]);

        return $this;
    }
}