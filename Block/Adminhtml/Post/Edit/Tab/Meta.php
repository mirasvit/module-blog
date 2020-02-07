<?php

namespace Mirasvit\Blog\Block\Adminhtml\Post\Edit\Tab;

use Magento\Backend\Block\Widget\Context;
use Magento\Backend\Block\Widget\Form;
use Magento\Framework\Data\FormFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Registry;
use Mirasvit\Blog\Model\Post;

class Meta extends Form
{
    /**
     * @var FormFactory
     */
    protected $formFactory;

    /**
     * @var Registry
     */
    protected $registry;

    /**
     * @param FormFactory                           $formFactory
     * @param Registry                              $registry
     * @param Context $context
     * @param array                                 $data
     */
    public function __construct(
        FormFactory $formFactory,
        Registry $registry,
        Context $context,
        array $data = []
    ) {
        $this->formFactory = $formFactory;
        $this->registry    = $registry;
        $this->context     = $context;

        parent::__construct($context, $data);
    }

    /**
     * @return $this
     * @throws LocalizedException
     */
    protected function _prepareForm()
    {
        $form = $this->formFactory->create();
        $this->setForm($form);

        /** @var Post $post */
        $post = $this->registry->registry('current_model');

        $fieldset = $form->addFieldset('edit_fieldset', [
            'class' => 'blog__post-fieldset',
        ]);

        $fieldset->addField('meta_title', 'text', [
            'label' => __('Meta Title'),
            'name'  => 'post[meta_title]',
            'value' => $post->getMetaTitle(),
        ]);

        $fieldset->addField('meta_description', 'textarea', [
            'label' => __('Meta Description'),
            'name'  => 'post[meta_description]',
            'value' => $post->getMetaDescription(),
        ]);

        $fieldset->addField('meta_keywords', 'textarea', [
            'label' => __('Meta Keywords'),
            'name'  => 'post[meta_keywords]',
            'value' => $post->getMetaKeywords(),
        ]);

        $fieldset->addField('url_key', 'text', [
            'label' => __('URL Key'),
            'name'  => 'post[url_key]',
            'value' => $post->getUrlKey(),
        ]);

        return parent::_prepareForm();
    }
}
