<?php

namespace Mirasvit\Blog\Block\Adminhtml\Post\Edit\Sidebar;

use Magento\Backend\Block\Widget\Context;
use Magento\Backend\Block\Widget\Form;
use Magento\Framework\Data\FormFactory;
use Magento\Framework\Registry;
use Mirasvit\Blog\Model\Post;
use Mirasvit\Blog\Model\ResourceModel\Tag\Collection;

class Tags extends Form
{
    /**
     * @var string
     */
    protected $_template = 'post/edit/sidebar/tags.phtml';

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
     * @return Collection
     */
    public function getTags()
    {
        /** @var Post $post */
        $post = $this->registry->registry('current_model');

        return $post->getTags();
    }
}
