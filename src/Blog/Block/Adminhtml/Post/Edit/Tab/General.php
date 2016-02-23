<?php

namespace Mirasvit\Blog\Block\Adminhtml\Post\Edit\Tab;

use Magento\Framework\Data\FormFactory;
use Magento\Framework\Registry;

class General extends \Magento\Backend\Block\Widget\Form
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
     * @var \Mirasvit\Blog\Model\Post\Attribute\Source\Status
     */
    protected $status;

    public function __construct(
        \Magento\Store\Model\System\Store $systemStore,
        FormFactory $formFactory,
        Registry $registry,
        \Magento\Backend\Block\Widget\Context $context,
        \Mirasvit\Blog\Model\Post\Attribute\Source\Status $status,
        array $data = []
    ) {
        $this->formFactory = $formFactory;
        $this->registry = $registry;
        $this->context = $context;
        $this->status = $status;

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

        if ($post->getId()) {
            $fieldset->addField('entity_id', 'hidden', [
                'name'  => 'entity_id',
                'value' => $post->getId(),
            ]);
        }

        $fieldset->addField('name', 'text', [
            'label'    => __('Title'),
            'name'     => 'name',
            'value'    => $post->getName(),
            'required' => true,
        ]);

        $fieldset->addField('short_content', 'editor', [
            'label'   => __('Short Content'),
            'name'    => 'short_content',
            'value'   => $post->getShortContent(),
            'wysiwyg' => true,
            'style'   => 'height:10em',
        ]);

        $fieldset->addField('content', 'editor', [
            'label'   => __('Content'),
            'name'    => 'content',
            'value'   => $post->getContent(),
            'wysiwyg' => true,
            'style'   => 'height:35em',
        ]);

        $fieldset->addField('url_key', 'text', [
            'label' => __('URL Key'),
            'name'  => 'url_key',
            'value' => $post->getUrlKey(),
        ]);


        return parent::_prepareForm();
    }
}
