<?php

namespace Mirasvit\Blog\Model\ResourceModel\Post;

use Magento\Catalog\Model\Product;
use Magento\Eav\Model\Entity\Collection\AbstractCollection;
use Mirasvit\Blog\Api\Data\PostInterface;
use Mirasvit\Blog\Model\Author;
use Mirasvit\Blog\Model\Category;
use Mirasvit\Blog\Model\Post;
use Mirasvit\Blog\Model\Tag;

class Collection extends AbstractCollection
{
    public function _afterLoad()
    {
        foreach ($this->_items as $item) {
            $item->load($item->getId());
        }

        return parent::_afterLoad();
    }

    /**
     * @return $this
     */
    public function addVisibilityFilter()
    {
        $this->addAttributeToFilter(PostInterface::STATUS, PostInterface::STATUS_PUBLISHED);
        $this->addFieldToFilter(PostInterface::TYPE, PostInterface::TYPE_POST);
        $this->addFieldToFilter(PostInterface::CREATED_AT, ['lteq' => date(DATE_ATOM)]);

        return $this;
    }

    /**
     * @param Category $category
     *
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
     * @param int $storeId
     *
     * @return $this
     */
    public function addStoreFilter($storeId)
    {
        // NOT EXISTS is compatibility for prev versions
        $this->getSelect()
            ->where("EXISTS (SELECT * FROM `{$this->getTable('mst_blog_store_post')}`
                AS `store_post`
                WHERE e.entity_id = store_post.post_id
                AND store_post.store_id in (?))
                OR NOT EXISTS (SELECT * FROM `{$this->getTable('mst_blog_store_post')}`
                AS `store_post`
                WHERE e.entity_id = store_post.post_id)", [0, $storeId]);

        return $this;
    }

    /**
     * @param Product $product
     *
     * @return $this
     */
    public function addRelatedProductFilter($product)
    {
        $this->getSelect()
            ->where("EXISTS (SELECT * FROM `{$this->getTable('mst_blog_post_product')}`
                AS `post_product`
                WHERE e.entity_id = post_product.post_id
                AND post_product.product_id in (?))", [0, $product->getId()]);

        return $this;
    }

    /**
     * @param Tag|array $tag
     *
     * @return $this
     */
    public function addTagFilter($tag)
    {
        $ids = [];
        if (is_object($tag)) {
            $ids[] = $tag->getId();
        } else {
            $ids = $tag;
        }

        $ids[] = 0;

        $this->getSelect()
            ->where("EXISTS (SELECT * FROM `{$this->getTable('mst_blog_tag_post')}`
                AS `tag_post`
                WHERE e.entity_id = tag_post.post_id
                AND tag_post.tag_id in (?))", $ids);

        return $this;
    }

    /**
     * @param string $q
     *
     * @return $this
     */
    public function addSearchFilter($q)
    {
        $likeExpression = $this->_resourceHelper->addLikeEscape($q, ['position' => 'any']);

        $this->addAttributeToSelect('name');
        $this->addAttributeToSelect(['content', 'short_content'], 'left');

        $this->addAttributeToFilter([
            ['attribute' => 'name', 'like' => $likeExpression],
            ['attribute' => 'content', 'like' => $likeExpression],
            ['attribute' => 'short_content', 'like' => $likeExpression],
        ]);

        return $this;
    }

    /**
     * @param Author $author
     *
     * @return $this
     */
    public function addAuthorFilter($author)
    {
        $this->addFieldToFilter('author_id', $author->getId());

        return $this;
    }

    /**
     * @return $this
     */
    public function addPostFilter()
    {
        $this->addFieldToFilter('type', Post::TYPE_POST);

        return $this;
    }

    /**
     * @return $this
     */
    public function defaultOrder()
    {
        $this->addAttributeToSort('is_pinned', self::SORT_ORDER_DESC);
        $this->getSelect()->order('created_at DESC');

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    protected function _construct()
    {
        $this->_init('Mirasvit\Blog\Model\Post', 'Mirasvit\Blog\Model\ResourceModel\Post');
    }
}
