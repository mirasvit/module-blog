<?php

namespace Mirasvit\Blog\Ui\Post\Source;

use Magento\Framework\Option\ArrayInterface;
use Mirasvit\Blog\Api\Repository\AuthorRepositoryInterface;


class Author implements ArrayInterface
{
    /**
     * @var AuthorRepositoryInterface
     */
    private $authorRepository;

    public function __construct(
        AuthorRepositoryInterface $authorRepository
    ) {
        $this->authorRepository = $authorRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function toOptionArray()
    {
        $result = [];
        foreach ($this->authorRepository->getCollection() as $user) {
            $result[] = [
                'label' => $user->getName(),
                'value' => $user->getId(),
            ];
        }

        return $result;
    }
}
