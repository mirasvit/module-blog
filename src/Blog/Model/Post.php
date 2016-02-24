<?php

namespace Mirasvit\Blog\Model;

use Magento\Framework\Model\AbstractExtensibleModel;
use Magento\Framework\Api\AttributeValueFactory;
use Magento\Framework\Model\Context;
use Magento\Framework\Registry;
use Magento\Framework\Api\ExtensionAttributesFactory;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\UrlInterface;

/**
 * @method string getName()
 * @method string getShortContent()
 * @method string getContent()
 * @method string getUrlKey()
 * @method string getMetaTitle()
 * @method string getMetaDescription()
 * @method string getMetaKeywords()
 * @method int getStatus()
 *
 * @method int getParentId()
 * @method $this setParentId($parent)
 *
 * @method string getType()
 * @method $this setType($type)
 * @method ResourceModel\Post getResource()
 */
class Post extends AbstractExtensibleModel
{
    const ENTITY = 'blog_post';

    const TYPE_POST = 'post';
    const TYPE_REVISION = 'revision';

    /**
     * @var UrlInterface
     */
    protected $urlManager;

    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var CategoryFactory
     */
    protected $categoryFactory;

    public function __construct(
        CategoryFactory $categoryFactory,
        UrlInterface $urlManager,
        StoreManagerInterface $storeManager,
        Context $context,
        Registry $registry,
        ExtensionAttributesFactory $extensionFactory,
        AttributeValueFactory $customAttributeFactory
    ) {
        $this->categoryFactory = $categoryFactory;
        $this->urlManager = $urlManager;
        $this->storeManager = $storeManager;

        parent::__construct($context, $registry, $extensionFactory, $customAttributeFactory);
    }

    protected function _construct()
    {
        $this->_init('Mirasvit\Blog\Model\ResourceModel\Post');
    }

    /**
     * Retrieve assigned category Ids
     *
     * @return array
     */
    public function getCategoryIds()
    {
        if (!$this->hasData('category_ids')) {
            $ids = $this->getResource()->getCategoryIds($this);
            $this->setData('category_ids', $ids);
        }

        return (array)$this->_getData('category_ids');
    }

    /**
     * @return \Mirasvit\Blog\Model\Category|false
     */
    public function getCategory()
    {
        $ids = $this->getCategoryIds();
        if (count($ids) == 0) {
            return false;
        }

        $categoryId = reset($ids);
        $category = $this->categoryFactory->create()->load($categoryId);

        return $category;
    }

    /**
     * @return Post
     */
    public function saveAsRevision()
    {
        $clone = clone $this;
        $clone->setId('')
            ->setParentId($this->getId())
            ->setType(self::TYPE_REVISION)
            ->save();

        echo '<pre>';
        print_r($clone->getData());

        return $clone;
    }
}