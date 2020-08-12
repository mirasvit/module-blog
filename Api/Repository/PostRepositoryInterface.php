<?php

namespace Mirasvit\Blog\Api\Repository;

use Magento\Framework\Exception\StateException;
use Mirasvit\Blog\Api\Data\PostInterface;
use Mirasvit\Blog\Model\ResourceModel\Post\Collection;

interface PostRepositoryInterface
{
    /**
     * @return Collection | Mirasvit\Blog\Api\Data\PostInterface[]
     */
    public function getCollection();

    /**
     * @return PostInterface
     */
    public function create();

    /**
     * @param Mirasvit\Blog\Api\Data\PostInterface $model
     *
     * @return Mirasvit\Blog\Api\Data\PostInterface
     */
    public function save(PostInterface $model);

    /**
     * @return Mirasvit\Blog\Api\Data\PostInterface[]
     */
    public function getList();

    /**
     * @param int $id
     *
     * @return Mirasvit\Blog\Api\Data\PostInterface|false
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
     * @param Mirasvit\Blog\Api\Data\PostInterface $post
     *
     * @return Mirasvit\Blog\Api\Data\PostInterface
     * @throws StateException
     */
    public function update($id, PostInterface $post);

    /**
     * @param Mirasvit\Blog\Api\Data\PostInterface $model
     *
     * @return bool
     */
    public function delete(PostInterface $model);
}
