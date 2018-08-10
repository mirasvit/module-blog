<?php

namespace Mirasvit\Blog\Ui\Post\Form\Control;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

class PreviewButton extends GenericButton implements ButtonProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function getButtonData()
    {
        return [
            'label'      => __('Preview'),
            'class'      => 'preview',
            'on_click'   => '',
            'sort_order' => 0,
        ];
    }
}
