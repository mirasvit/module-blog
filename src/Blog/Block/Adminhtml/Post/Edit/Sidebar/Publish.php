<?php

namespace Mirasvit\Blog\Block\Adminhtml\Post\Edit\Sidebar;

use Magento\Framework\Data\FormFactory;
use Magento\Framework\Registry;
use Mirasvit\Blog\Model\Post\Attribute\Source\Status;
use Magento\Backend\Block\Widget\Context;

class Publish extends \Magento\Backend\Block\Widget\Form
{
    /**
     * @var Status
     */
    protected $status;

    /**
     * @var FormFactory
     */
    protected $formFactory;

    /**
     * @var Registry
     */
    protected $registry;


    public function __construct(
        Status $status,
        FormFactory $formFactory,
        Registry $registry,
        Context $context,
        array $data = []
    ) {
        $this->status = $status;
        $this->formFactory = $formFactory;
        $this->registry = $registry;

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

        $fieldset = $form->addFieldset('publish_fieldset', [
            'class'  => 'blog__post-fieldset',
            'legend' => __('Publish'),
        ]);

        $fieldset->addField('status', 'select', [
            'label'  => __('Status'),
            'name'   => 'status',
            'value'  => $post->getStatus(),
            'values' => $this->status->toOptionArray(),
        ]);

        $dateFormat = $this->_localeDate->getDateFormat(\IntlDateFormatter::SHORT);

        $fieldset->addField('created_at', 'date', [
            'label'       => __('Published on'),
            'name'        => 'created_at',
            'value'       => $post->getCreatedAt(),
            'date_format' => $dateFormat
        ]);

        return parent::_prepareForm();
    }
}
