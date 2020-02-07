<?php

namespace Mirasvit\Blog\Block\Adminhtml\Author\Edit;

use Magento\Backend\Block\Widget\Context;
use Magento\Backend\Block\Widget\Form as WidgetForm;
use Magento\Framework\Data\FormFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Registry;
use Mirasvit\Blog\Model\Author;

class Form extends WidgetForm
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
        $form = $this->formFactory->create()->setData([
            'id'      => 'edit_form',
            'action'  => $this->getUrl('*/*/save', ['id' => $this->getRequest()->getParam('id')]),
            'method'  => 'post',
            'enctype' => 'multipart/form-data',
        ]);

        $form->setUseContainer(true);
        $this->setForm($form);

        $this->setForm($form);

        /** @var Author $author */
        $author = $this->registry->registry('current_model');

        $fieldset = $form->addFieldset('edit_fieldset', [
            'legend' => __('Author Information'),
        ]);

        if ($author->getId()) {
            $fieldset->addField('user_id', 'hidden', [
                'name'  => 'user_id',
                'value' => $author->getId(),
            ]);
        }

        $fieldset->addField('display_name', 'text', [
            'label'    => __('Display Name'),
            'name'     => 'display_name',
            'value'    => $author->getDisplayName(),
            'required' => true,
        ]);

        $fieldset->addField('bio', 'textarea', [
            'label'    => __('Biographical Info'),
            'name'     => 'bio',
            'value'    => $author->getBio(),
            'required' => false,
        ]);

        return parent::_prepareForm();
    }
}
