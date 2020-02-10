<?php

namespace Mirasvit\Blog\Model\Config\Source;

class CommentProvider
{
    /**
     * @return array
     */
    public function getAllOptions()
    {
        return $this->toOptionArray();
    }

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
                'value' => 'disqus',
            ],
            [
                'label' => 'Facebook',
                'value' => 'facebook',
            ],
        ];

        return $result;
    }
}
