<?php

namespace Mirasvit\Blog\Model;

use Magento\Framework\Image as MagentoImage;
use Magento\Framework\Image\Factory as ImageFactory;
use Magento\Framework\Model\AbstractExtensibleModel;
use Magento\Framework\Api\AttributeValueFactory;
use Magento\Framework\Model\Context;
use Magento\Framework\Registry;
use Magento\Framework\Api\ExtensionAttributesFactory;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\DataObject\IdentityInterface;
use Mirasvit\Blog\Model\ResourceModel\Tag\CollectionFactory as TagCollectionFactory;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory as ProductCollectionFactory;

/**
 * @method string getName()
 * @method string getShortContent()
 * @method string getContent()
 * @method string getUrlKey()
 * @method string getMetaTitle()
 * @method string getMetaDescription()
 * @method string getMetaKeywords()
 * @method string getFeaturedImage()
 * @method string getFeaturedAlt()
 * @method string getFeaturedShowOnHome()
 * @method $this setFeaturedImage($image)
 * @method int getStatus()
 * @method string getCreatedAt()
 *
 * @method int getParentId()
 * @method $this setParentId($parent)
 *
 * @method string getType()
 * @method $this setType($type)
 * @method ResourceModel\Post getResource()
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class Post extends AbstractExtensibleModel implements IdentityInterface
{
    const ENTITY = 'blog_post';
    const CACHE_TAG = 'blog_post';

    const TYPE_POST = 'post';
    const TYPE_REVISION = 'revision';

    /**
     * @var MagentoImage
     */
    protected $_processor;

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
     * @var ProductCollectionFactory
     */
    protected $productCollectionFactory;

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
     * @param ProductCollectionFactory   $productCollectionFactory
     * @param AuthorFactory              $authorFactory
     * @param Config                     $config
     * @param Url                        $url
     * @param StoreManagerInterface      $storeManager
     * @param ImageFactory               $imageFactory
     * @param Context                    $context
     * @param Registry                   $registry
     * @param ExtensionAttributesFactory $extensionFactory
     * @param AttributeValueFactory      $customAttributeFactory
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        CategoryFactory $postFactory,
        TagCollectionFactory $tagCollectionFactory,
        AuthorFactory $authorFactory,
        ProductCollectionFactory $productCollectionFactory,
        Config $config,
        Url $url,
        StoreManagerInterface $storeManager,
        ImageFactory $imageFactory,
        Context $context,
        Registry $registry,
        ExtensionAttributesFactory $extensionFactory,
        AttributeValueFactory $customAttributeFactory
    ) {
        $this->categoryFactory          = $postFactory;
        $this->tagCollectionFactory     = $tagCollectionFactory;
        $this->productCollectionFactory = $productCollectionFactory;
        $this->authorFactory            = $authorFactory;
        $this->config                   = $config;
        $this->url                      = $url;
        $this->storeManager             = $storeManager;
        $this->imageFactory             = $imageFactory;

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
        return [Category::CACHE_TAG, self::CACHE_TAG . '_' . $this->getId()];
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
     * Retrieve assigned store Ids
     *
     * @return array
     */
    public function getStoreIds()
    {
        if (!$this->hasData('store_ids')) {
            $ids = $this->getResource()->getStoreIds($this);
            $this->setData('store_ids', $ids);
        }
        if (!$this->_getData('store_ids')) {
            $this->setData('store_ids', [0]);
        }

        return (array)$this->_getData('store_ids');
    }

    /**
     * Retrieve assigned product Ids
     *
     * @return array
     */
    public function getRelatedProductIds()
    {
        if (!$this->hasData('product_ids')) {
            $ids = $this->getResource()->getProductIds($this);
            $this->setData('product_ids', $ids);
        }

        return (array)$this->_getData('product_ids');
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
            ->addAttributeToSelect(['name', 'url_key'])
            ->addFieldToFilter('entity_id', $ids);

        return $collection;
    }

    /**
     * @return \Magento\Store\Model\Store|false
     */
    public function getStore()
    {
        $ids = $this->getStoreIds();
        if (count($ids) == 0) {
            return false;
        }

        $storeId = reset($ids);
        $store   = $this->storeManager->getStore($storeId);

        return $store;
    }

    /**
     * @param int $storeId
     * @return bool
     */
    public function isStoreAllowed($storeId)
    {
        return in_array(0, $this->getStoreIds()) || in_array($storeId, $this->getStoreIds());
    }

    /**
     * @return \Magento\Catalog\Model\ResourceModel\Product\Collection
     */
    public function getRelatedProducts()
    {
        $ids = $this->getRelatedProductIds();
        $ids[] = 0;

        $collection = $this->productCollectionFactory->create()
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

    /**
     * @param int $width
     * @param int $height
     * @return string
     */
    public function getWidgetFeaturedImageUrl($width = 0, $height = 0)
    {
        $dirname = '';
        if ($width && $height && $this->getFeaturedImage()) {
            $dirname   = $width . 'x' . $height . DIRECTORY_SEPARATOR;
            $filename  = $this->config->getWidgetMediaPath($dirname) . $this->getFeaturedImage();
            $processor = $this->getImageProcessor();
            $processor->resize($width, $height);
            $processor->save($filename);
        }

        return $this->config->getMediaUrl($dirname . $this->getFeaturedImage());
    }

    /**
     * @return MagentoImage
     */
    protected function getImageProcessor()
    {
        if (!$this->_processor) {
            $filename = $this->config->getMediaPath() . DIRECTORY_SEPARATOR . $this->getFeaturedImage();
            $this->_processor = $this->imageFactory->create($filename);
        }
        $this->_processor->keepAspectRatio(true);
        $this->_processor->keepFrame(false);
        $this->_processor->keepTransparency(true);
        $this->_processor->constrainOnly(true);

        return $this->_processor;
    }
}