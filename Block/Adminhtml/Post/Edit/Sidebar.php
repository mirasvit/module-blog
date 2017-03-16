<?php

namespace Mirasvit\Blog\Block\Adminhtml\Post\Edit;

use Magento\Backend\Block\Template;
use Magento\Backend\Block\Template\Context;
use Magento\Framework\Registry;
use Magento\Framework\Locale\ResolverInterface;

class Sidebar extends Template
{
    /**
     * @var string
     */
    protected $_template = "post/edit/sidebar.phtml";

    /**
     * @var Registry
     */
    protected $registry;

    /**
     * @param Registry $registry
     * @param Context  $context
     */
    public function __construct(
        ResolverInterface $localeResolver,
        Registry $registry,
        Context $context
    ) {
        $this->localeResolver = $localeResolver;
        $this->registry       = $registry;

        parent::__construct($context);
    }

    /**
     * @return \Mirasvit\Blog\Model\Post
     */
    public function getPost()
    {
        return $this->registry->registry('current_model');
    }

    /**
     * @param string $param
     * @param string $default
     * @return string
     * @throws \Zend_Locale_Exception
     */
    public function getLocaleData($param, $default = '')
    {
        try {
            $text = \Zend_Locale_Data::getContent($this->localeResolver->getLocale(), $param);
        } catch (\Exception $e) {
            $text = $default;
        }

        return $text;
    }
}
