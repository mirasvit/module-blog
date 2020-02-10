<?php

namespace Mirasvit\Blog\Block\Adminhtml\Post\Edit\Tab;

use Magento\Backend\Block\Widget\Context;
use Magento\Backend\Block\Widget\Form;
use Magento\Cms\Model\Wysiwyg\Config as WysiwygConfig;
use Magento\Framework\Data\FormFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Registry;
use Mirasvit\Blog\Model\Post;

class General extends Form
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
     * @var WysiwygConfig
     */
    protected $wysiwygConfig;

    /**
     * @param WysiwygConfig $wysiwygConfig
     * @param FormFactory   $formFactory
     * @param Registry      $registry
     * @param Context       $context
     */
    public function __construct(
        WysiwygConfig $wysiwygConfig,
        FormFactory $formFactory,
        Registry $registry,
        Context $context
    ) {
        $this->wysiwygConfig = $wysiwygConfig;
        $this->formFactory   = $formFactory;
        $this->registry      = $registry;

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

        $fieldset = $form->addFieldset('edit_fieldset', [
            'class' => 'blog__post-fieldset',
        ]);

        if ($post->getId()) {
            $fieldset->addField('entity_id', 'hidden', [
                'name'  => 'post[entity_id]',
                'value' => $post->getId(),
            ]);
        }

        $fieldset->addField('name', 'text', [
            'label'    => __('Title'),
            'name'     => 'post[name]',
            'value'    => $post->getName(),
            'required' => true,
        ]);

        $editorConfig = $this->wysiwygConfig->getConfig(['tab_id' => $this->getTabId()]);

        $fieldset->addField('content', 'editor', [
            'name'    => 'post[content]',
            'value'   => $post->getContent(),
            'wysiwyg' => true,
            'style'   => 'height:35em',
            'config'  => $editorConfig,
        ]);

        $fieldset->addField('short_content', 'editor', [
            'label'   => __('Excerpt'),
            'name'    => 'post[short_content]',
            'value'   => $post->getShortContent(),
            'wysiwyg' => true,
            'style'   => 'height:5em',
            'config'  => $editorConfig,
        ]);


        return parent::_prepareForm();
    }
}
