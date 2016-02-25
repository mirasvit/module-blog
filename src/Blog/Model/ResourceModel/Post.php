<?php
namespace Mirasvit\Blog\Model\ResourceModel;

use Magento\Framework\DataObject;
use Magento\Eav\Model\Entity\AbstractEntity;
use Magento\Eav\Model\Entity\Context;
use Mirasvit\Blog\Model\Config;

class Post extends AbstractEntity
{
    /**
     * @var Config
     */
    protected $config;

    /**
     * @param Config  $config
     * @param Context $context
     * @param array   $data
     */
    public function __construct(
        Config $config,
        Context $context,
        $data = []
    ) {
        $this->config = $config;

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
     * {@inheritdoc}
     */
    protected function _beforeSave(DataObject $post)
    {
        if (!$post->hasData('type')) {
            $post->setData('type', \Mirasvit\Blog\Model\Post::TYPE_POST);
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
        $oldFileName = $post->getFeaturedImage();
        $newFileName = $post->getId() . '_' . md5($image['name']) . '.' . $ext;

        $allowedFileExtensions = ['png', 'jpeg', 'jpg', 'gif'];
        $ext = pathinfo($image['name'], PATHINFO_EXTENSION);

        if (!in_array($ext, $allowedFileExtensions)) {
            throw new \Exception(
                __('File type not allowed (only JPG, JPEG, PNG & GIF files are allowed)')
            );
        }

        $uploader = new \Magento\Framework\File\Uploader($_FILES['featured_image']);
        $uploader->setAllowedExtensions($allowedFileExtensions)
            ->setAllowRenameFiles(false)
            ->setFilesDispersion(false)
            ->setAllowCreateFolders(true)
            ->setAllowRenameFiles(false)
            ->setFilesDispersion(false);
        $uploader->save($this->config->getMediaPath(), $newFileName);

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