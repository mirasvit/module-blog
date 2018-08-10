<?php

namespace Mirasvit\Blog\Ui\Post\Source;

use Magento\Framework\Option\ArrayInterface;
use Mirasvit\Blog\Api\Data\PostInterface;

class Status implements ArrayInterface
{
    /**
     * {@inheritdoc}
     */
    public function toOptionArray()
    {
        return [
            [
                'label' => __('Draft'),
                'value' => PostInterface::STATUS_DRAFT,
            ],
            [
                'label' => __('Pending Review'),
                'value' => PostInterface::STATUS_PENDING_REVIEW,
            ],
            [
                'label' => __('Published'),
                'value' => PostInterface::STATUS_PUBLISHED,
            ],
        ];
    }
}
