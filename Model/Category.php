<?php

namespace Mirasvit\Blog\Model;

use Magento\Framework\Model\AbstractExtensibleModel;
use Magento\Framework\Api\AttributeValueFactory;
use Magento\Framework\Model\Context;
use Magento\Framework\Registry;
use Magento\Framework\Api\ExtensionAttributesFactory;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\DataObject\IdentityInterface;

/**
 * @method string getName()
 * @method string getContent()
 * @method string getUrlKey()
 *
 * @method string getPath()
 * @method $this setPath($path)
 *
 * @method int getLevel()
 *
 * @method int getChildrenCount()
 * @method $this setChildrenCount($count)
 *
 * @method int getPosition()
 * @method $this setPosition($position)
 *
 * @method string getMetaTitle()
 * @method string getMetaDescription()
 * @method string getMetaKeywords()
 * @method int getStatus()
 *
 * @method int getParentId()
 * @method $this setParentId($id)
 * @method bool hasParentId()
 */
class Category extends AbstractExtensibleModel implements IdentityInterface, UrlInterface
{
    const ENTITY = 'blog_category';

    const CACHE_TAG = 'blog_category';

    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var Url
     */
    protected $url;

    /**
     * @param Url                        $url
     * @param StoreManagerInterface      $storeManager
     * @param Context                    $context
     * @param Registry                   $registry
     * @param ExtensionAttributesFactory $extensionFactory
     * @param AttributeValueFactory      $customAttributeFactory
     */
    public function __construct(
        Url $url,
        StoreManagerInterface $storeManager,
        Context $context,
        Registry $registry,
        ExtensionAttributesFactory $extensionFactory,
        AttributeValueFactory $customAttributeFactory
    ) {
        $this->url = $url;
        $this->storeManager = $storeManager;

        parent::__construct($context, $registry, $extensionFactory, $customAttributeFactory);
    }

    /**
     * {@inheritdoc}
     */
    protected function _construct()
    {
        $this->_init('Mirasvit\Blog\Model\ResourceModel\Category');
    }

    /**
     * {@inheritdoc}
     */
    public function getIdentities()
    {
        return [self::CACHE_TAG, self::CACHE_TAG . '_' . $this->getId()];
    }

    /**
     * @return array
     */
    public function getPathIds()
    {
        $ids = $this->getData('path_ids');
        if ($ids === null) {
            $ids = explode('/', $this->getPath());
            $this->setData('path_ids', $ids);
        }

        return $ids;
    }

    /**
     * Get all parent categories ids
     *
     * @return array
     */
    public function getParentIds()
    {
        return array_diff($this->getPathIds(), [$this->getId()]);
    }

    /**
     * @param array $urlParams
     * @return string
     */
    public function getUrl($urlParams = [])
    {
        return $this->url->getCategoryUrl($this, $urlParams);
    }
}