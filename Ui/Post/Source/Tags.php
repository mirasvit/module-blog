<?php

namespace Mirasvit\Blog\Ui\Post\Source;

use Magento\Framework\Option\ArrayInterface;
use Mirasvit\Blog\Api\Repository\TagRepositoryInterface;

class Tags implements ArrayInterface
{
    private $tagRepository;

    public function __construct(
        TagRepositoryInterface $tagRepository
    ) {
        $this->tagRepository = $tagRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function toOptionArray()
    {
        $result = [];

        foreach ($this->tagRepository->getCollection() as $tag) {
            $result[] = [
                'label' => $tag->getName(),
                'value' => $tag->getId(),
            ];
        }

        return $result;
    }
}
