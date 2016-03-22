<?php

namespace Mirasvit\Blog\Model\Config\Source;

class CommentProvider
{
    /**
     * @return array
     */
    public function toOptionArray()
    {
        $result = [
            [
                'label' => __('Disable comments'),
                'value' => '',
            ],
            [
                'label' => 'Disqus',
                'value' => 'disqus'
            ]
        ];

        return $result;
    }

    /**
     * @return array
     */
    public function getAllOptions()
    {
        return $this->toOptionArray();
    }
}
