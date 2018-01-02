<?php

namespace Mirasvit\Blog\Model;

use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\Context;
use Magento\Framework\Registry;

/**
 * @method string getName()
 * @method $this setName($name)
 *
 * @method string getUrlKey()
 */
class Tag extends AbstractModel implements IdentityInterface, UrlInterface
{
    const CACHE_TAG = 'blog_tag';

    /**
     * @var Url
     */
    protected $url;

    /**
     * @param Url      $url
     * @param Context  $context
     * @param Registry $registry
     */
    public function __construct(
        Url $url,
        Context $context,
        Registry $registry
    ) {
        $this->url = $url;

        parent::__construct($context, $registry);
    }

    /**
     * Get identities.
     *
     * @return array
     */
    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }

    /**
     * {@inheritdoc}
     */
    protected function _construct()
    {
        $this->_init('Mirasvit\Blog\Model\ResourceModel\Tag');
    }

    /**
     * @param string $tag
     * @return $this
     */
    public function getOrCreate($tag)
    {
        $tag = trim($tag);
        $this->load($tag, 'name');

        if (!$this->getId()) {
            $this->setName($tag)
                ->save();
        }

        return $this;
    }

    /**
     * @param array $urlParams
     * @return string
     */
    public function getUrl($urlParams = [])
    {
        return $this->url->getTagUrl($this, $urlParams);
    }
}
