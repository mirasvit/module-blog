<?php

namespace Mirasvit\Blog\Api\Repository;

use Mirasvit\Blog\Api\Data\CategoryInterface;
use Mirasvit\Blog\Model\ResourceModel\Category\Collection;

interface CategoryRepositoryInterface
{
    /**
     * @return Collection | CategoryInterface[]
     */
    public function getCollection();

    /**
     * @return CategoryInterface
     */
    public function create();

    /**
     * @param CategoryInterface $model
     *
     * @return CategoryInterface
     */
    public function save(CategoryInterface $model);

    /**
     * @param int $id
     *
     * @return CategoryInterface|false
     */
    public function get($id);

    /**
     * @param CategoryInterface $model
     *
     * @return bool
     */
    public function delete(CategoryInterface $model);
}
