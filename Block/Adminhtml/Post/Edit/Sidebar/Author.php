<?php

namespace Mirasvit\Blog\Block\Adminhtml\Post\Edit\Sidebar;

use Magento\Backend\Block\Widget\Context;
use Magento\Backend\Block\Widget\Form;
use Magento\Framework\Data\FormFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Registry;
use Mirasvit\Blog\Model\Post;
use Mirasvit\Blog\Model\Post\Attribute\Source\Author as AuthorSource;

class Author extends Form
{
    /**
     * @var AuthorSource
     */
    protected $authorSource;

    /**
     * @var FormFactory
     */
    protected $formFactory;

    /**
     * @var Registry
     */
    protected $registry;

    /**
     * @param AuthorSource $authorSource
     * @param FormFactory  $formFactory
     * @param Registry     $registry
     * @param Context      $context
     */
    public function __construct(
        AuthorSource $authorSource,
        FormFactory $formFactory,
        Registry $registry,
        Context $context
    ) {
        $this->authorSource = $authorSource;
        $this->formFactory  = $formFactory;
        $this->registry     = $registry;

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

        $fieldset = $form->addFieldset('tags_fieldset', [
            'class'  => 'blog__post-fieldset',
            'legend' => __('Author'),
        ]);

        $fieldset->addField('author_id', 'select', [
            'name'   => 'post[author_id]',
            'value'  => $post->getAuthorId(),
            'values' => $this->authorSource->toOptionArray(),
        ]);

        return parent::_prepareForm();
    }
}
