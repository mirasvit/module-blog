<?php

namespace Mirasvit\Blog\Block\Adminhtml\Post\Edit\Sidebar;

use Magento\Framework\Data\FormFactory;
use Magento\Framework\Registry;
use Mirasvit\Blog\Helper\Form\Post\Storeview as BlogStoreview;
use Magento\Backend\Block\Widget\Context;

class Stores extends \Magento\Backend\Block\Widget\Form
{
    /**
     * @var BlogStoreview
     */
    protected $blogStoreview;

    /**
     * @var FormFactory
     */
    protected $formFactory;

    /**
     * @var Registry
     */
    protected $registry;

    /**
     * @param BlogStoreview $blogStoreview
     * @param FormFactory   $formFactory
     * @param Registry      $registry
     * @param Context       $context
     * @param array         $data
     */
    public function __construct(
        BlogStoreview $blogStoreview,
        FormFactory $formFactory,
        Registry $registry,
        Context $context,
        array $data = []
    ) {
        $this->blogStoreview = $blogStoreview;
        $this->formFactory   = $formFactory;
        $this->registry      = $registry;

        parent::__construct($context, $data);
    }

    /**
     * @return $this
     *
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function _prepareForm()
    {
        $form = $this->formFactory->create();
        $this->setForm($form);

        /** @var \Mirasvit\Blog\Model\Post $post */
        $post = $this->registry->registry('current_model');

        $fieldset = $form->addFieldset('stores_fieldset', [
            'class'  => 'blog__post-fieldset',
            'legend' => __('Store Views'),
        ]);

        if ($this->blogStoreview->isMultiStore()) {
            $container = 'blog_post_store_views';
            $fieldset->addField('store_ids', 'hidden', [
                'name'             => 'post[store_ids]',
                'value'            => implode(',', $post->getStoreIds()),
                'after_element_js' => $this->blogStoreview->getField(
                    $post, $container
                ),
            ]);
        } else {
            $fieldset->addField('store_ids', 'hidden', [
                'name'  => 'post[store_ids]',
                'value' => 0,
            ]);
            $fieldset->addField('store_ids_note', 'note', [
                'text' => __('All Store Views'),
            ]);
        }

        return parent::_prepareForm();
    }
}
