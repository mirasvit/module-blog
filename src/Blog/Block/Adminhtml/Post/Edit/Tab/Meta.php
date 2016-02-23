<?php

namespace Mirasvit\Blog\Block\Adminhtml\Post\Edit\Tab;

use Magento\Framework\Data\FormFactory;
use Magento\Framework\Registry;

class Meta extends \Magento\Backend\Block\Widget\Form
{
    /**
     * @var FormFactory
     */
    protected $formFactory;

    /**
     * @var Registry
     */
    protected $registry;


    public function __construct(
        \Magento\Store\Model\System\Store $systemStore,
        FormFactory $formFactory,
        Registry $registry,
        \Magento\Backend\Block\Widget\Context $context,
        array $data = []
    ) {
        $this->formFactory = $formFactory;
        $this->registry = $registry;
        $this->context = $context;

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

        $fieldset = $form->addFieldset('edit_fieldset', [
            'class' => 'blog__post-fieldset'
        ]);

        $fieldset->addField('meta_title', 'text', [
            'label' => __('Meta Title'),
            'name'  => 'meta_title',
            'value' => $post->getMetaTitle(),
        ]);

        $fieldset->addField('meta_description', 'textarea', [
            'label' => __('Meta Description'),
            'name'  => 'meta_description',
            'value' => $post->getMetaDescription(),
        ]);

        $fieldset->addField('meta_keywords', 'textarea', [
            'label' => __('Meta Keywords'),
            'name'  => 'meta_keywords',
            'value' => $post->getMetaKeywords(),
        ]);

        return parent::_prepareForm();
    }
}
