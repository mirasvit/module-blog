<?php

namespace Mirasvit\Blog\Block\Adminhtml\Post\Edit\Sidebar;

use Magento\Backend\Block\Widget\Context;
use Magento\Backend\Block\Widget\Form;
use Magento\Framework\Data\FormFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Registry;
use Mirasvit\Blog\Model\Post;
use Mirasvit\Blog\Model\ResourceModel\Category\CollectionFactory as CategoryCollectionFactory;

class Categories extends Form
{
    /**
     * @var CategoryCollectionFactory
     */
    protected $categoryCollectionFactory;

    /**
     * @var FormFactory
     */
    protected $formFactory;

    /**
     * @var Registry
     */
    protected $registry;

    /**
     * @param CategoryCollectionFactory $postCollectionFactory
     * @param FormFactory               $formFactory
     * @param Registry                  $registry
     * @param Context                   $context
     * @param array                     $data
     */
    public function __construct(
        CategoryCollectionFactory $postCollectionFactory,
        FormFactory $formFactory,
        Registry $registry,
        Context $context,
        array $data = []
    ) {
        $this->categoryCollectionFactory = $postCollectionFactory;
        $this->formFactory               = $formFactory;
        $this->registry                  = $registry;

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

        $fieldset = $form->addFieldset('categories_fieldset', [
            'class'  => 'blog__post-fieldset',
            'legend' => __('Categories'),
        ]);

        $collection = $this->categoryCollectionFactory->create()
            ->addAttributeToSelect(['name']);

        $fieldset->addField('category_ids', 'checkboxes', [
            'name'   => 'post[category_ids][]',
            'value'  => $post->getCategoryIds(),
            'values' => $collection->toOptionArray(),
        ]);

        return parent::_prepareForm();
    }
}
