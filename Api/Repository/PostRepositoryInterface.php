<?php

namespace Mirasvit\Blog\Api\Repository;

use Magento\Framework\Exception\StateException;
use Mirasvit\Blog\Api\Data\PostInterface;
use Mirasvit\Blog\Model\ResourceModel\Post\Collection;

interface PostRepositoryInterface
{
    /**
     * @return Collection | PostInterface[]
     */
    public function getCollection();

    /**
     * @return PostInterface
     */
    public function create();

    /**
     * @param PostInterface $model
     *
     * @return PostInterface
     */
    public function save(PostInterface $model);

    /**
     * @return PostInterface[]
     */
    public function getList();

    /**
     * @param int $id
     *
     * @return PostInterface|false
     */
    public function get($id);

    /**
     * @param int $id
     *
     * @return bool
     * @throws StateException
     */
    public function apiDelete($id);

    /**
     * @param int                                   $id
     * @param PostInterface $post
     *
     * @return PostInterface
     * @throws StateException
     */
    public function update($id, PostInterface $post);

    /**
     * @param PostInterface $model
     *
     * @return bool
     */
    public function delete(PostInterface $model);
}
