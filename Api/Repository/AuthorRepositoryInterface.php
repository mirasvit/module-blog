<?php

namespace Mirasvit\Blog\Api\Repository;

use Mirasvit\Blog\Api\Data\CategoryInterface;
use Mirasvit\Blog\Api\Data\PostInterface;

interface AuthorRepositoryInterface
{
    /**
     * @return \Magento\User\Model\ResourceModel\User\Collection | \Magento\User\Model\User[]
     */
    public function getCollection();

    /**
     * @param int $id
     * @return \Magento\User\Model\User|false
     */
    public function get($id);

}