<?php

namespace Mirasvit\Blog\Block\Adminhtml\Category\Edit;

class Tabs extends \Magento\Backend\Block\Widget\Tabs
{
    protected function _construct()
    {
        parent::_construct();
        $this->setDestElementId('edit_form');
    }

    /**
     * {@inheritdoc}
     */
    protected function _beforeToHtml()
    {
        $this->addTab('general_section', [
            'label'   => __('General Information'),
            'title'   => __('General Information'),
            'content' => $this->getLayout()
                ->createBlock('\Mirasvit\Blog\Block\Adminhtml\Category\Edit\Tab\General')->toHtml(),
        ]);

        $this->addTab('meta_section', [
            'label'   => __('Meta Information'),
            'title'   => __('Meta Information'),
            'content' => $this->getLayout()
                ->createBlock('\Mirasvit\Blog\Block\Adminhtml\Category\Edit\Tab\Meta')->toHtml(),
        ]);

        return parent::_beforeToHtml();
    }
}
