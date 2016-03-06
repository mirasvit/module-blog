<?php
namespace Mirasvit\Blog\Model\ResourceModel\Post;

use Magento\Eav\Model\Entity\Collection\AbstractCollection;
use Mirasvit\Blog\Model\Post;
use Mirasvit\Blog\Model\Post\Attribute\Source\Status;

class Collection extends AbstractCollection
{
    /**
     * {@inheritdoc}
     */
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

    /**
     * @param \Mirasvit\Blog\Model\Tag $tag
     * @return $this
     */
    public function addTagFilter($tag)
    {
        $this->getSelect()
            ->where("EXISTS (SELECT * FROM `{$this->getTable('mst_blog_tag_post')}`
                AS `tag_post`
                WHERE e.entity_id = tag_post.post_id
                AND tag_post.tag_id in (?))", [0, $tag->getId()]);

        return $this;
    }

    /**
     * @param string $q
     * @return $this
     */
    public function addSearchFilter($q)
    {
        $likeExpression = $this->_resourceHelper->addLikeEscape($q, ['position' => 'any']);

        $this->addAttributeToFilter([
            ['attribute' => 'name', 'like' => $likeExpression],
            ['attribute' => 'content', 'like' => $likeExpression],
            ['attribute' => 'short_content', 'like' => $likeExpression]
        ]);

        return $this;
    }

    /**
     * @return $this
     */
    public function addPostFilter()
    {
        $this->addFieldToFilter('type', \Mirasvit\Blog\Model\Post::TYPE_POST);

        return $this;
    }
}