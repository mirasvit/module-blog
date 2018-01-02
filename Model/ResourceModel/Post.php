<?php
namespace Mirasvit\Blog\Model\ResourceModel;

use Magento\Framework\DataObject;
use Magento\Eav\Model\Entity\AbstractEntity;
use Magento\Eav\Model\Entity\Context;
use Mirasvit\Blog\Model\Config;
use Magento\Framework\Filter\FilterManager;
use Mirasvit\Blog\Model\TagFactory as TagModelFactory;
use Magento\Framework\File\Uploader as FileUploader;

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

    /**
     * @param Config          $config
     * @param TagModelFactory $tagFactory
     * @param FilterManager   $filter
     * @param Context         $context
     * @param array           $data
     */
    public function __construct(
        Config $config,
        TagModelFactory $tagFactory,
        FilterManager $filter,
        Context $context,
        $data = []
    ) {
        $this->tagFactory = $tagFactory;
        $this->config = $config;
        $this->filter = $filter;

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
     * @param \Mirasvit\Blog\Model\Post $post
     * @return array
     */
    public function getStoreIds($post)
    {
        $connection = $this->getConnection();

        $select = $connection->select()->from(
            $this->getTable('mst_blog_store_post'),
            'store_id'
        )->where(
            'post_id = ?',
            (int)$post->getId()
        );

        return $connection->fetchCol($select);
    }

    /**
     * @param \Mirasvit\Blog\Model\Post $post
     * @return array
     */
    public function getTagIds($post)
    {
        $connection = $this->getConnection();

        $select = $connection->select()->from(
            $this->getTable('mst_blog_tag_post'),
            'tag_id'
        )->where(
            'post_id = ?',
            (int)$post->getId()
        );

        return $connection->fetchCol($select);
    }

    /**
     * @param \Mirasvit\Blog\Model\Post $post
     * @return array
     */
    public function getProductIds($post)
    {
        $connection = $this->getConnection();

        $select = $connection->select()->from(
            $this->getTable('mst_blog_post_product'),
            'product_id'
        )->where(
            'post_id = ?',
            (int)$post->getId()
        );

        return $connection->fetchCol($select);
    }

    /**
     * {@inheritdoc}
     */
    protected function _beforeSave(DataObject $post)
    {
        /** @var \Mirasvit\Blog\Model\Post $post */

        if (!$post->hasData('type')) {
            $post->setData('type', \Mirasvit\Blog\Model\Post::TYPE_POST);
        }

        if (!$post->getData('url_key')) {
            $post->setData('url_key', $this->filter->translitUrl($post->getName()));
        }

        $this->saveImage($post);

        return parent::_beforeSave($post);
    }

    /**
     * {@inheritdoc}
     */
    protected function _afterSave(DataObject $post)
    {
        /** @var \Mirasvit\Blog\Model\Post $post */
        $this->saveCategories($post);
        $this->saveStores($post);
        $this->saveTags($post);
        $this->saveProducts($post);

        return parent::_afterSave($post);
    }

    /**
     * @param \Mirasvit\Blog\Model\Post $post
     * @return $this
     */
    protected function saveProducts($post)
    {
        $table = $this->getTable('mst_blog_post_product');

        if (!$post->hasProductIds()) {
            return $this;
        }

        $productIds = $post->getProductIds();

        $oldProductIds = $this->getProductIds($post);

        $insert = array_diff($productIds, $oldProductIds);
        $delete = array_diff($oldProductIds, $productIds);

        $connection = $this->getConnection();
        if (!empty($insert)) {
            $data = [];
            foreach ($insert as $productId) {
                if (empty($productId)) {
                    continue;
                }
                $data[] = [
                    'product_id' => (int)$productId,
                    'post_id'    => (int)$post->getId()
                ];
            }

            if ($data) {
                $connection->insertMultiple($table, $data);
            }
        }

        if (!empty($delete)) {
            foreach ($delete as $productId) {
                $where = ['post_id = ?' => (int)$post->getId(), 'product_id = ?' => (int)$productId];
                $connection->delete($table, $where);
            }
        }

        return $this;
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

    /**
     * @param \Mirasvit\Blog\Model\Post $post
     * @return $this
     */
    protected function saveStores($post)
    {
        $table = $this->getTable('mst_blog_store_post');

        /**
         * If store ids data is not declared we haven't do manipulations
         */
        if (!$post->hasStoreIds()) {
            return $this;
        }

        $storeIds    = $post->getStoreIds();
        $oldStoreIds = $this->getStoreIds($post);

        $insert = array_diff($storeIds, $oldStoreIds);
        $delete = array_diff($oldStoreIds, $storeIds);

        $connection = $this->getConnection();
        if (!empty($insert)) {
            $data = [];
            foreach ($insert as $storeId) {
                if (empty($storeId)) {
                    continue;
                }
                $data[] = [
                    'store_id' => (int)$storeId,
                    'post_id'  => (int)$post->getId()
                ];
            }

            if ($data) {
                $connection->insertMultiple($table, $data);
            }
        }

        if (!empty($delete)) {
            foreach ($delete as $storeId) {
                $where = ['post_id = ?' => (int)$post->getId(), 'store_id = ?' => (int)$storeId];
                $connection->delete($table, $where);
            }
        }

        return $this;
    }

    /**
     * @param \Mirasvit\Blog\Model\Post $post
     * @return $this
     */
    protected function saveTags($post)
    {
        $table = $this->getTable('mst_blog_tag_post');

        if (!$post->hasTagNames()) {
            return $this;
        }

        $oldTagIds = $post->getTagIds();
        $tagIds = [];

        foreach ($post->getTagNames() as $tag) {
            $tagIds[] = $this->tagFactory->create()
                ->getOrCreate($tag)
                ->getId();
        }

        $tagIds = array_unique($tagIds);

        $insert = array_diff($tagIds, $oldTagIds);
        $delete = array_diff($oldTagIds, $tagIds);

        $connection = $this->getConnection();
        if (!empty($insert)) {
            $data = [];
            foreach ($insert as $tagId) {
                if (empty($tagId)) {
                    continue;
                }
                $data[] = [
                    'tag_id'  => (int)$tagId,
                    'post_id' => (int)$post->getId()
                ];
            }

            if ($data) {
                $connection->insertMultiple($table, $data);
            }
        }

        if (!empty($delete)) {
            foreach ($delete as $tagId) {
                $where = ['post_id = ?' => (int)$post->getId(), 'tag_id = ?' => (int)$tagId];
                $connection->delete($table, $where);
            }
        }

        return $this;
    }

    /**
     * @param \Mirasvit\Blog\Model\Post $post
     * @return $this
     * @throws \Exception
     */
    protected function saveImage($post)
    {
        if (!isset($_FILES['featured_image']) || !$_FILES['featured_image']['name']) {
            return $this;
        }

        $image = $_FILES['featured_image'];

        $ext = pathinfo($image['name'], PATHINFO_EXTENSION);
        $name = pathinfo($image['name'], PATHINFO_FILENAME);
        $oldFileName = $post->getFeaturedImage();
        $newFileName = $name . '-' . $post->getId() . '.' . $ext;

        $allowedFileExtensions = ['png', 'jpeg', 'jpg', 'gif'];
        $ext = pathinfo($image['name'], PATHINFO_EXTENSION);

        if (!in_array($ext, $allowedFileExtensions)) {
            throw new \Exception(
                __('File type not allowed (only JPG, JPEG, PNG & GIF files are allowed)')
            );
        }

        $uploader = new FileUploader($_FILES['featured_image']);
        $uploader->setAllowedExtensions($allowedFileExtensions)
            ->setAllowRenameFiles(false)
            ->setFilesDispersion(false)
            ->setAllowCreateFolders(true)
            ->setAllowRenameFiles(false)
            ->setFilesDispersion(false);
        $result = $uploader->save($this->config->getMediaPath(), $newFileName);
        $newFileName = $result['file'];

        $post->setFeaturedImage($newFileName);

        if ($newFileName != $oldFileName) {
            $this->deleteImage($oldFileName);
        }

        return $this;
    }

    /**
     * @param string $fileName
     * @return void
     */
    private function deleteImage($fileName)
    {
        $path = $this->config->getMediaPath();
        if ($fileName && file_exists($path . '/' . $fileName)) {
            unlink($path . '/' . $fileName);
        }

        return;
    }
}