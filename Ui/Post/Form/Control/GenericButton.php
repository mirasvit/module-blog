<?php


namespace Mirasvit\Blog\Ui\Post\Form\Control;

use Magento\Backend\Block\Widget\Context;
use Mirasvit\Blog\Api\Data\PostInterface;

class GenericButton
{
    /**
     * @var Context
     */
    private $context;

    public function __construct(
        Context $context
    ) {
        $this->context = $context;
    }

    /**
     * @return int|null
     */
    public function getId()
    {
        return $this->context->getRequest()->getParam(PostInterface::ID);
    }

    /**
     * {@inheritdoc}
     */
    public function getUrl($route = '', $params = [])
    {
        return $this->context->getUrlBuilder()->getUrl($route, $params);
    }
}
