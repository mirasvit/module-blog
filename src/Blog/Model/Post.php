<?php

namespace Mirasvit\Blog\Model;

use Magento\Framework\Model\AbstractExtensibleModel;
use Magento\Framework\Api\AttributeValueFactory;
use Magento\Framework\Model\Context;
use Magento\Framework\Registry;
use Magento\Framework\Api\ExtensionAttributesFactory;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\DataObject\IdentityInterface;
use Mirasvit\Blog\Model\ResourceModel\Tag\CollectionFactory as TagCollectionFactory;

/**
 * @method string getName()
 * @method string getShortContent()
 * @method string getContent()
 * @method string getUrlKey()
 * @method string getMetaTitle()
 * @method string getMetaDescription()
 * @method string getMetaKeywords()
 * @method int getStatus()
 * @method string getCreatedAt()
 *
 * @method int getParentId()
 * @method $this setParentId($parent)
 *
 * @method string getType()
 * @method $this setType($type)
 * @method ResourceModel\Post getResource()
 */
class Post extends AbstractExtensibleModel implements IdentityInterface
{
    const ENTITY = 'blog_post';
    const CACHE_TAG = 'blog_post';

    const TYPE_POST = 'post';
    const TYPE_REVISION = 'revision';

    /**
     * @var Url
     */
    protected $url;

    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var CategoryFactory
     */
    protected $categoryFactory;

    /**
     * @var TagCollectionFactory
     */
    protected $tagCollectionFactory;

    /**
     * @var AuthorFactory
     */
    protected $authorFactory;

    /**
     * @var Config
     */
    protected $config;

    /**
     * @param CategoryFactory            $postFactory
     * @param TagCollectionFactory       $tagCollectionFactory
     * @param AuthorFactory              $authorFactory
     * @param Config                     $config
     * @param Url                        $url
     * @param StoreManagerInterface      $storeManager
     * @param Context                    $context
     * @param Registry                   $registry
     * @param ExtensionAttributesFactory $extensionFactory
     * @param AttributeValueFactory      $customAttributeFactory
     */
    public function __construct(
        CategoryFactory $postFactory,
        TagCollectionFactory $tagCollectionFactory,
        AuthorFactory $authorFactory,
        Config $config,
        Url $url,
        StoreManagerInterface $storeManager,
        Context $context,
        Registry $registry,
        ExtensionAttributesFactory $extensionFactory,
        AttributeValueFactory $customAttributeFactory
    ) {
        $this->categoryFactory = $postFactory;
        $this->tagCollectionFactory = $tagCollectionFactory;
        $this->authorFactory = $authorFactory;
        $this->config = $config;
        $this->url = $url;
        $this->storeManager = $storeManager;

        parent::__construct($context, $registry, $extensionFactory, $customAttributeFactory);
    }

    /**
     * {@inheritdoc}
     */
    protected function _construct()
    {
        $this->_init('Mirasvit\Blog\Model\ResourceModel\Post');
    }

    /**
     * {@inheritdoc}
     */
    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
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
     * @return ResourceModel\Category\Collection
     */
    public function getCategories()
    {
        $ids = $this->getCategoryIds();
        $ids[] = 0;

        $collection = $this->categoryFactory->create()->getCollection()
            ->addAttributeToSelect('*')
            ->addFieldToFilter('entity_id', $ids);

        return $collection;
    }

    /**
     * @return array
     */
    public function getTagIds()
    {
        return (array)$this->getResource()->getTagIds($this);
    }

    /**
     * @return ResourceModel\Tag\Collection
     */
    public function getTags()
    {
        $ids = $this->getTagIds();
        $ids[] = 0;

        $collection = $this->tagCollectionFactory->create()
            ->addFieldToFilter('tag_id', $ids);

        return $collection;
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

        return $clone;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url->getPostUrl($this);
    }

    /**
     * @return Author
     */
    public function getAuthor()
    {
        if (!$this->hasData('author')) {
            $this->setData('author', $this->authorFactory->create()->load($this->getAuthorId()));
        }

        return $this->getData('author');
    }

    /**
     * @return string
     */
    public function getFeaturedImageUrl()
    {
        return $this->config->getMediaUrl($this->getFeaturedImage());
    }
}