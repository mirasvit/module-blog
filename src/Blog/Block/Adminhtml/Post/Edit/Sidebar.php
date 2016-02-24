<?php

namespace Mirasvit\Blog\Block\Adminhtml\Post\Edit;

use Magento\Backend\Block\Template;
use Magento\Backend\Block\Template\Context;
use Magento\Framework\Registry;

class Sidebar extends Template
{
    protected $_template = "post/edit/sidebar.phtml";

    /**
     * @var Registry
     */
    protected $registry;

    public function __construct(
        Registry $registry,
        Context $context
    ) {
        $this->registry = $registry;

        parent::__construct($context);
    }

    /**
     * @return \Mirasvit\Blog\Model\Post
     */
    public function getPost()
    {
        return $this->registry->registry('current_model');
    }
}
