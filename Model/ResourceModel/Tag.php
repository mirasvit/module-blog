<?php

namespace Mirasvit\Blog\Model\ResourceModel;

use Magento\Framework\Filter\FilterManager;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magento\Framework\Model\ResourceModel\Db\Context;
use Mirasvit\Blog\Model\Config;

class Tag extends AbstractDb
{
    /**
     * @var Config
     */
    protected $config;

    /**
     * @var FilterManager
     */
    protected $filter;

    /**
     * @param Config        $config
     * @param FilterManager $filter
     * @param Context       $context
     * @param string        $connectionName
     */
    public function __construct(
        Config $config,
        FilterManager $filter,
        Context $context,
        $connectionName = null
    ) {
        $this->config = $config;
        $this->filter = $filter;

        parent::__construct($context, $connectionName);
    }

    /**
     * {@inheritdoc}
     */
    protected function _construct()
    {
        $this->_init('mst_blog_tag', 'tag_id');
    }

    /**
     * {@inheritdoc}
     */
    protected function _beforeSave(AbstractModel $tag)
    {
        /** @var \Mirasvit\Blog\Model\Tag $tag */

        if (!$tag->getData('url_key')) {
            $tag->setData('url_key', $this->filter->translitUrl($tag->getName()));
        }

        return parent::_beforeSave($tag);
    }
}
