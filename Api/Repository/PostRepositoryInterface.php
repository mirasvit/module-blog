<?php

namespace Mirasvit\Blog\Api\Repository;

use Mirasvit\Blog\Api\Data\PostInterface;

interface PostRepositoryInterface
{
    /**
     * @return \Mirasvit\Blog\Model\ResourceModel\Post\Collection | PostInterface[]
     */
    public function getCollection();

    /**
     * @return PostInterface
     */
    public function create();

    /**
     * @param PostInterface $model
     * @return PostInterface
     */
    public function save(PostInterface $model);

    /**
     * @param int $id
     * @return PostInterface|false
     */
    public function get($id);

    /**
     * @param PostInterface $model
     * @return bool
     */
    public function delete(PostInterface $model);
}