<?php

namespace Mirasvit\Blog\Block\Adminhtml\Post\Edit;

class Tabs extends \Magento\Backend\Block\Widget\Tabs
{
    /**
     * {@inheritdoc}
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('blog__post-tabs');
        $this->setDestElementId('blog_details');
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
                ->createBlock('\Mirasvit\Blog\Block\Adminhtml\Post\Edit\Tab\General')->toHtml(),
        ]);

        $this->addTab('meta_section', [
            'label'   => __('Meta Information'),
            'title'   => __('Meta Information'),
            'content' => $this->getLayout()
                ->createBlock('\Mirasvit\Blog\Block\Adminhtml\Post\Edit\Tab\Meta')->toHtml(),
        ]);

        return parent::_beforeToHtml();
    }
}
