<?php

namespace Mirasvit\Blog\Api\Repository;

use Magento\User\Model\ResourceModel\User\Collection;
use Magento\User\Model\User;

interface AuthorRepositoryInterface
{
    /**
     * @return Collection | User[]
     */
    public function getCollection();

    /**
     * @param int $id
     *
     * @return User|false
     */
    public function get($id);
}
