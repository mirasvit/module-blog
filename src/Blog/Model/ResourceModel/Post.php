<?php
namespace Mirasvit\Blog\Model\ResourceModel;

use Magento\Framework\DataObject;
use Magento\Eav\Model\Entity\AbstractEntity;

class Post extends AbstractEntity
{
    public function getEntityType()
    {
        if (empty($this->_type)) {
            $this->setType(\MIrasvit\Blog\Model\Post::ENTITY);
        }
        return parent::getEntityType();
    }

    /**
     * @param \Mirasvit\Blog\Model\Post $post
     * @return array
     */
    public function getCategoryIds($post)
    {
        $connection = $this->getConnection();

        $select = $connection->select()->from(
            $this->getTable('mst_blog_category_post'),
            'category_id'
        )->where(
            'post_id = ?',
            (int)$post->getId()
        );

        return $connection->fetchCol($select);
    }

    /**
     * {@inheritdoc}
     */
    protected function _afterSave(DataObject $post)
    {
        /** @var \Mirasvit\Blog\Model\Post $post */
        $this->saveCategories($post);

        return parent::_afterSave($post);
    }

    /**
     * @param \Mirasvit\Blog\Model\Post $post
     * @return $this
     */
    protected function saveCategories($post)
    {
        $table = $this->getTable('mst_blog_category_post');

        /**
         * If category ids data is not declared we haven't do manipulations
         */
        if (!$post->hasCategoryIds()) {
            return $this;
        }

        $categoryIds = $post->getCategoryIds();
        $oldCategoryIds = $this->getCategoryIds($post);

        $insert = array_diff($categoryIds, $oldCategoryIds);
        $delete = array_diff($oldCategoryIds, $categoryIds);

        $connection = $this->getConnection();
        if (!empty($insert)) {
            $data = [];
            foreach ($insert as $categoryId) {
                if (empty($categoryId)) {
                    continue;
                }
                $data[] = [
                    'category_id' => (int)$categoryId,
                    'post_id'     => (int)$post->getId()
                ];
            }

            if ($data) {
                $connection->insertMultiple($table, $data);
            }
        }

        if (!empty($delete)) {
            foreach ($delete as $categoryId) {
                $where = ['post_id = ?' => (int)$post->getId(), 'category_id = ?' => (int)$categoryId];
                $connection->delete($table, $where);
            }
        }

        return $this;
    }
}