<?php

namespace Mirasvit\Blog\Block\Adminhtml\Post\Edit\Sidebar;

use Magento\Backend\Block\Widget\Context;
use Magento\Backend\Block\Widget\Form;
use Magento\Framework\Data\FormFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Registry;
use Mirasvit\Blog\Model\Post;
use Mirasvit\Blog\Model\Post\Attribute\Source\Status;

class Publish extends Form
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

    /**
     * @param Status      $status
     * @param FormFactory $formFactory
     * @param Registry    $registry
     * @param Context     $context
     * @param array       $data
     */
    public function __construct(
        Status $status,
        FormFactory $formFactory,
        Registry $registry,
        Context $context,
        array $data = []
    ) {
        $this->status      = $status;
        $this->formFactory = $formFactory;
        $this->registry    = $registry;

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

        $fieldset = $form->addFieldset('publish_fieldset', [
            'class'  => 'blog__post-fieldset',
            'legend' => __('Publish'),
        ]);

        $fieldset->addField('status', 'select', [
            'label'  => __('Status'),
            'name'   => 'post[status]',
            'value'  => $post->getStatus(),
            'values' => $this->status->toOptionArray(),
        ]);

        $fieldset->addField('created_at', 'date', [
            'label'       => __('Published on'),
            'name'        => 'post[created_at]',
            'value'       => $post->getCreatedAt(),
            'date_format' => 'MMM d, y',
            'time_format' => 'h:mm a',
        ]);

        $fieldset->addField('is_pinned', 'checkbox', [
            'label'   => __('Pin post at the top'),
            'name'    => 'post[is_pinned]',
            'value'   => 1,
            'checked' => $post->getIsPinned(),
        ]);

        return parent::_prepareForm();
    }
}
