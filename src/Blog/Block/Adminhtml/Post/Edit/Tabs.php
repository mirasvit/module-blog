<?php

namespace Mirasvit\Blog\Block\Adminhtml\Post\Edit;

class Tabs extends \Magento\Backend\Block\Widget\Tabs
{
    /**
     * @var \Magento\Backend\Block\Widget\Context
     */
    protected $context;

    /**
     * @var \Magento\Framework\Json\EncoderInterface
     */
    protected $jsonEncoder;

    /**
     * @var \Magento\Backend\Model\Auth\Session
     */
    protected $authSession;

    /**
     * @param \Magento\Backend\Block\Widget\Context    $context
     * @param \Magento\Framework\Json\EncoderInterface $jsonEncoder
     * @param \Magento\Backend\Model\Auth\Session      $authSession
     * @param array                                    $data
     */
    public function __construct(
        \Magento\Backend\Block\Widget\Context $context,
        \Magento\Framework\Json\EncoderInterface $jsonEncoder,
        \Magento\Backend\Model\Auth\Session $authSession,
        array $data = []
    ) {
        $this->context = $context;
        $this->jsonEncoder = $jsonEncoder;
        $this->authSession = $authSession;
        parent::__construct($context, $jsonEncoder, $authSession, $data);
    }

    /**
     *
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('blog__post-tabs');
        $this->setDestElementId('blog_details');
    }

    /**
     * @return $this
     * @throws \Exception
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function _beforeToHtml()
    {
        $this->addTab('general_section', [
            'label'   => __('General Information'),
            'title'   => __('General Information'),
            'content' => $this->getLayout()
                ->createBlock('\Mirasvit\Blog\Block\Adminhtml\Post\Edit\Tab\General')->toHtml(),
        ]);

        $this->addTab('meta_section', [
            'label'   => __('Meta Information'),
            'title'   => __('Meta Information'),
            'content' => $this->getLayout()
                ->createBlock('\Mirasvit\Blog\Block\Adminhtml\Post\Edit\Tab\Meta')->toHtml(),
        ]);

        return parent::_beforeToHtml();
    }

    /************************/
}
