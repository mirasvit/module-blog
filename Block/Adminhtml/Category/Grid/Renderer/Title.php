<?php

namespace Mirasvit\Blog\Block\Adminhtml\Category\Grid\Renderer;

use Magento\Backend\Block\Widget\Grid\Column\Renderer\AbstractRenderer;
use Magento\Framework\DataObject;

class Title extends AbstractRenderer
{
    /**
     * {@inheritdoc}
     */
    public function render(DataObject $row)
    {
        return str_repeat('&nbsp;', $row->getLevel() * 10) . $row->getName();
    }
}
