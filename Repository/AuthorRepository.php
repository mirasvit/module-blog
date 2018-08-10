<?php

namespace Mirasvit\Blog\Repository;

use Mirasvit\Blog\Api\Repository\AuthorRepositoryInterface;
use Magento\User\Model\ResourceModel\User\CollectionFactory;
use Mirasvit\Blog\Model\AuthorFactory;

class AuthorRepository implements AuthorRepositoryInterface
{
    /**
     * @var AuthorFactory
     */
    private $factory;

    /**
     * @var CollectionFactory
     */
    private $collectionFactory;

    public function __construct(
        AuthorFactory $factory,
        CollectionFactory $collectionFactory
    ) {
        $this->factory = $factory;
        $this->collectionFactory = $collectionFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function getCollection()
    {
        return $this->collectionFactory->create();
    }

    /**
     * {@inheritdoc}
     */
    public function get($id)
    {
        $user = $this->factory->create()->load($id);

        return $user->getId() ? $user : false;
    }
}