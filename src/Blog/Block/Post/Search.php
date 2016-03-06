<?php

namespace Mirasvit\Blog\Block\Post;

use Magento\Framework\View\Element\Template;
use Magento\Framework\Registry;
use Magento\Framework\View\Element\Template\Context;
use Mirasvit\Blog\Model\Url;

class Search extends Template
{
    /**
     * @var Registry
     */
    protected $registry;

    /**
     * @var Context
     */
    protected $context;

    /**
     * @var
     */
    protected $url;

    /**
     * @param Url      $url
     * @param Registry $registry
     * @param Context  $context
     * @param array    $data
     */
    public function __construct(
        Url $url,
        Registry $registry,
        Context $context,
        array $data = []
    ) {
        $this->url = $url;
        $this->registry = $registry;
        $this->context = $context;

        parent::__construct($context, $data);
    }

    /**
     * @return string
     */
    public function getSearchUrl()
    {
        return $this->url->getSearchUrl();
    }
}
