<?php

namespace Mirasvit\Blog\Block\Adminhtml;

use Magento\Backend\Block\Widget\Grid\Container;

class Author extends Container
{
    /**
     * {@inheritdoc}
     */
    protected function _construct()
    {
        $this->_controller = 'adminhtml_author';
        $this->_blockGroup = 'Mirasvit_Blog';

        parent::_construct();
    }
}
