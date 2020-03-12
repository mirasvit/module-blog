<?php

namespace Mirasvit\Blog\Model\ResourceModel;

use Magento\Eav\Model\Entity\AbstractEntity;
use Magento\Eav\Model\Entity\Context;
use Magento\Framework\DataObject;
use Magento\Framework\Filter\FilterManager;
use Mirasvit\Blog\Api\Data\PostInterface;
use Mirasvit\Blog\Model\Config;
use Mirasvit\Blog\Model\TagFactory as TagModelFactory;

class Post extends AbstractEntity
{
    /**
     * @var Config
     */
    protected $config;

    /**
     * @var FilterManager
     */
    protected $filter;

    /**
     * @var TagModelFactory
     */
    protected $tagFactory;

    public function __construct(
        Config $config,
        TagModelFactory $tagFactory,
        FilterManager $filter,
        Context $context,
        $data = []
    ) {
        $this->tagFactory = $tagFactory;
        $this->config     = $config;
        $this->filter     = $filter;

        parent::__construct($context, $data);
    }

    /**
     * {@inheritdoc}
     */
    public function getEntityType()
    {
        if (empty($this->_type)) {
            $this->setType(\MIrasvit\Blog\Model\Post::ENTITY);
        }

        return parent::getEntityType();
    }

    protected function _afterLoad(DataObject $post)
    {
        /** @var PostInterface $post */

        $post->setCategoryIds($this->getCategoryIds($post));
        $post->setStoreIds($this->getStoreIds($post));
        $post->setTagIds($this->getTagIds($post));
        $post->setProductIds($this->getProductIds($post));

        return parent::_afterLoad($post);
    }

    /**
     * @param PostInterface $model
     *
     * @return array
     */
    private function getCategoryIds(PostInterface $model)
    {
        $connection = $this->getConnection();

        $select = $connection->select()->from(
            $this->getTable('mst_blog_category_post'),
            'category_id'
        )->where(
            'post_id = ?',
            (int)$model->getId()
        );

        return $connection->fetchCol($select);
    }

    /**
     * @param PostInterface $model
     *
     * @return array
     */
    private function getStoreIds(PostInterface $model)
    {
        $connection = $this->getConnection();

        $select = $connection->select()->from(
            $this->getTable('mst_blog_store_post'),
            'store_id'
        )->where(
            'post_id = ?',
            (int)$model->getId()
        );

        return $connection->fetchCol($select);
    }

    /**
     * @param PostInterface $model
     *
     * @return array
     */
    private function getTagIds(PostInterface $model)
    {
        $connection = $this->getConnection();
        $select     = $connection->select()->from(
            $this->getTable('mst_blog_tag_post'),
            'tag_id'
        )->where(
            'post_id = ?',
            (int)$model->getId()
        );

        return $connection->fetchCol($select);
    }

    /**
     * @param PostInterface $model
     *
     * @return array
     */
    private function getProductIds(PostInterface $model)
    {
        $connection = $this->getConnection();

        $select = $connection->select()->from(
            $this->getTable('mst_blog_post_product'),
            'product_id'
        )->where(
            'post_id = ?',
            (int)$model->getId()
        );

        return $connection->fetchCol($select);
    }

    /**
     * {@inheritdoc}
     */
    protected function _afterSave(DataObject $post)
    {
        /** @var PostInterface $post */
        $this->saveCategoryIds($post);
        $this->saveStoreIds($post);
        $this->saveTagIds($post);
        $this->saveProductIds($post);

        return parent::_afterSave($post);
    }

    /**
     * @param PostInterface $model
     *
     * @return $this
     */
    private function saveCategoryIds(PostInterface $model)
    {
        $connection = $this->getConnection();

        $table = $this->getTable('mst_blog_category_post');

        if (!$model->getCategoryIds()) {
            return $this;
        }

        $categoryIds    = $model->getCategoryIds();
        $oldCategoryIds = $this->getCategoryIds($model);

        $insert = array_diff($categoryIds, $oldCategoryIds);
        $delete = array_diff($oldCategoryIds, $categoryIds);

        if (!empty($insert)) {
            $data = [];
            foreach ($insert as $categoryId) {
                if (empty($categoryId)) {
                    continue;
                }

                $data[] = [
                    'category_id' => (int)$categoryId,
                    'post_id'     => (int)$model->getId(),
                ];
            }

            if ($data) {
                $connection->insertMultiple($table, $data);
            }
        }

        if (!empty($delete)) {
            foreach ($delete as $categoryId) {
                $where = ['post_id = ?' => (int)$model->getId(), 'category_id = ?' => (int)$categoryId];
                $connection->delete($table, $where);
            }
        }

        return $this;
    }

    /**
     * @param PostInterface $model
     *
     * @return $this
     */
    private function saveStoreIds(PostInterface $model)
    {
        $connection = $this->getConnection();

        $table = $this->getTable('mst_blog_store_post');

        /**
         * If store ids data is not declared we haven't do manipulations
         */
        if (!$model->getStoreIds()) {
            return $this;
        }

        $storeIds    = $model->getStoreIds();
        $oldStoreIds = $this->getStoreIds($model);

        $insert = array_diff($storeIds, $oldStoreIds);
        $delete = array_diff($oldStoreIds, $storeIds);

        if (!empty($insert)) {
            $data = [];
            foreach ($insert as $storeId) {
                if (empty($storeId)) {
                    continue;
                }
                $data[] = [
                    'store_id' => (int)$storeId,
                    'post_id'  => (int)$model->getId(),
                ];
            }

            if ($data) {
                $connection->insertMultiple($table, $data);
            }
        }

        if (!empty($delete)) {
            foreach ($delete as $storeId) {
                $where = ['post_id = ?' => (int)$model->getId(), 'store_id = ?' => (int)$storeId];
                $connection->delete($table, $where);
            }
        }

        return $this;
    }

    /**
     * @param PostInterface $model
     *
     * @return $this
     */
    private function saveTagIds(PostInterface $model)
    {
        $connection = $this->getConnection();

        $table = $this->getTable('mst_blog_tag_post');

        if (!$model->getTagIds()) {
            return $this;
        }

        $tagIds    = $model->getTagIds();
        $oldTagIds = $this->getTagIds($model);

        $insert = array_diff($tagIds, $oldTagIds);
        $delete = array_diff($oldTagIds, $tagIds);

        if (!empty($insert)) {
            $data = [];
            foreach ($insert as $tagId) {
                if (empty($tagId)) {
                    continue;
                }
                $data[] = [
                    'tag_id'  => (int)$tagId,
                    'post_id' => (int)$model->getId(),
                ];
            }

            if ($data) {
                $connection->insertMultiple($table, $data);
            }
        }

        if (!empty($delete)) {
            foreach ($delete as $tagId) {
                $where = ['post_id = ?' => (int)$model->getId(), 'tag_id = ?' => (int)$tagId];
                $connection->delete($table, $where);
            }
        }

        return $this;
    }

    /**
     * @param PostInterface $model
     *
     * @return $this
     */
    private function saveProductIds(PostInterface $model)
    {
        $connection = $this->getConnection();

        $table = $this->getTable('mst_blog_post_product');

        if (!$model->getProductIds()) {
            return $this;
        }

        $productIds    = $model->getProductIds();
        $oldProductIds = $this->getProductIds($model);

        $insert = array_diff($productIds, $oldProductIds);
        $delete = array_diff($oldProductIds, $productIds);

        if (!empty($insert)) {
            $data = [];
            foreach ($insert as $productId) {
                if (empty($productId)) {
                    continue;
                }
                $data[] = [
                    'product_id' => (int)$productId,
                    'post_id'    => (int)$model->getId(),
                ];
            }

            if ($data) {
                $connection->insertMultiple($table, $data);
            }
        }

        if (!empty($delete)) {
            foreach ($delete as $productId) {
                $where = ['post_id = ?' => (int)$model->getId(), 'product_id = ?' => (int)$productId];
                $connection->delete($table, $where);
            }
        }

        return $this;
    }
}
