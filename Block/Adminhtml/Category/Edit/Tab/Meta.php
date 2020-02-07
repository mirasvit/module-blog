<?php

namespace Mirasvit\Blog\Block\Adminhtml\Category\Edit\Tab;

use Magento\Backend\Block\Widget\Context;
use Magento\Backend\Block\Widget\Form;
use Magento\Framework\Data\FormFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Registry;
use Mirasvit\Blog\Model\Category;

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
     * @param FormFactory $formFactory
     * @param Registry    $registry
     * @param Context     $context
     */
    public function __construct(
        FormFactory $formFactory,
        Registry $registry,
        Context $context
    ) {
        $this->formFactory = $formFactory;
        $this->registry    = $registry;

        parent::__construct($context);
    }

    /**
     * @return $this
     * @throws LocalizedException
     */
    protected function _prepareForm()
    {
        $form = $this->formFactory->create();
        $this->setForm($form);

        /** @var Category $category */
        $category = $this->registry->registry('current_model');

        $fieldset = $form->addFieldset('edit_fieldset', [
            'legend' => __('Search Engine Optimization'),
        ]);

        $fieldset->addField('url_key', 'text', [
            'label' => __('URL Key'),
            'name'  => 'url_key',
            'value' => $category->getUrlKey(),
        ]);

        $fieldset->addField('meta_title', 'text', [
            'label' => __('Meta Title'),
            'name'  => 'meta_title',
            'value' => $category->getMetaTitle(),
        ]);

        $fieldset->addField('meta_description', 'textarea', [
            'label' => __('Meta Description'),
            'name'  => 'meta_description',
            'value' => $category->getMetaDescription(),
        ]);

        $fieldset->addField('meta_keywords', 'textarea', [
            'label' => __('Meta Keywords'),
            'name'  => 'meta_keywords',
            'value' => $category->getMetaKeywords(),
        ]);

        return parent::_prepareForm();
    }
}
