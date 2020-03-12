<?php

namespace Mirasvit\Blog\Block\Adminhtml\Post\Edit\Sidebar;

use Magento\Backend\Block\Widget\Context;
use Magento\Backend\Block\Widget\Form;
use Magento\Framework\Data\FormFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Registry;
use Mirasvit\Blog\Model\Post;

class Image extends Form
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

        /** @var Post $post */
        $post = $this->registry->registry('current_model');

        $fieldset = $form->addFieldset('image_fieldset', [
            'class'  => 'blog__post-fieldset',
            'legend' => __('Featured Image'),
        ]);

        $fieldset->addField('featured_image', 'image', [
            'required' => false,
            'name'     => 'featured_image',
            'value'    => $post->getFeaturedImageUrl(),
        ]);

        $fieldset->addField('featured_alt', 'text', [
            'required' => false,
            'label'    => __('Alt'),
            'name'     => 'post[featured_alt]',
            'value'    => $post->getFeaturedAlt(),
        ]);

        $fieldset->addField('featured_show_on_home', 'checkbox', [
            'label'   => __('Is show on Blog Home page'),
            'name'    => 'post[featured_show_on_home]',
            'value'   => 1,
            'checked' => $post->getFeaturedShowOnHome(),
        ]);

        return parent::_prepareForm();
    }
}
