<?php

namespace Mirasvit\Blog\Api\Data;

use Magento\Catalog\Model\ResourceModel\Product\Collection;

interface PostInterface
{
    const ID = 'entity_id';

    const NAME             = 'name';
    const TYPE             = 'type';
    const STATUS           = 'status';
    const AUTHOR_ID        = 'author_id';
    const SHORT_CONTENT    = 'short_content';
    const CONTENT          = 'content';
    const URL_KEY          = 'url_key';
    const META_TITLE       = 'meta_title';
    const META_DESCRIPTION = 'meta_description';
    const META_KEYWORDS    = 'meta_keywords';
    const FEATURED_IMAGE   = 'featured_image';
    const FEATURED_ALT     = 'featured_alt';

    const CREATED_AT = 'created_at';
    const IS_PINNED  = 'is_pinned';

    const CATEGORY_IDS = 'category_ids';
    const STORE_IDS    = 'store_ids';
    const TAG_IDS      = 'tag_ids';
    const PRODUCT_IDS  = 'product_ids';

    const TYPE_POST     = 'post';
    const TYPE_REVISION = 'revision';

    const STATUS_DRAFT          = 0;
    const STATUS_PENDING_REVIEW = 1;
    const STATUS_PUBLISHED      = 2;

    /**
     * @return int
     */
    public function getId();

    /**
     * @return string
     */
    public function getType();

    /**
     * @param string $value
     *
     * @return $this
     */
    public function setType($value);

    /**
     * @return string
     */
    public function getStatus();

    /**
     * @param string $value
     *
     * @return $this
     */
    public function setStatus($value);

    /**
     * @return int
     */
    public function getAuthorId();

    /**
     * @param int $value
     *
     * @return $this
     */
    public function setAuthorId($value);

    /**
     * @return string
     */
    public function getName();

    /**
     * @param string $value
     *
     * @return $this
     */
    public function setName($value);

    /**
     * @return string
     */
    public function getShortContent();

    /**
     * @param string $value
     *
     * @return $this
     */
    public function setShortContent($value);

    /**
     * @return string
     */
    public function getContent();

    /**
     * @param string $value
     *
     * @return $this
     */
    public function setContent($value);

    /**
     * @return string
     */
    public function getUrlKey();

    /**
     * @param string $value
     *
     * @return $this
     */
    public function setUrlKey($value);

    /**
     * @return string
     */
    public function getMetaTitle();

    /**
     * @param string $value
     *
     * @return $this
     */
    public function setMetaTitle($value);

    /**
     * @return string
     */
    public function getMetaDescription();

    /**
     * @param string $value
     *
     * @return $this
     */
    public function setMetaDescription($value);

    /**
     * @return string
     */
    public function getMetaKeywords();

    /**
     * @param string $value
     *
     * @return $this
     */
    public function setMetaKeywords($value);

    /**
     * @return string
     */
    public function getFeaturedImage();

    /**
     * @param string $value
     *
     * @return $this
     */
    public function setFeaturedImage($value);

    /**
     * @return string
     */
    public function getFeaturedAlt();

    /**
     * @param string $value
     *
     * @return $this
     */
    public function setFeaturedAlt($value);

    /**
     * @return string
     */
    public function getCreatedAt();

    /**
     * @param string $value
     *
     * @return $this
     */
    public function setCreatedAt($value);

    /**
     * @return string
     */
    public function isPinned();

    /**
     * @param string $value
     *
     * @return $this
     */
    public function setIsPinned($value);

    /**
     * @return mixed
     */
    public function getCategoryIds();

    /**
     * @param mixed $value
     *
     * @return $this
     */
    public function setCategoryIds(array $value);

    /**
     * @return mixed
     */
    public function getStoreIds();

    /**
     * @param mixed $value
     *
     * @return $this
     */
    public function setStoreIds(array $value);

    /**
     * @return mixed
     */
    public function getTagIds();

    /**
     * @param mixed $value
     *
     * @return $this
     */
    public function setTagIds(array $value);

    /**
     * @return mixed
     */
    public function getProductIds();

    /**
     * @param mixed $value
     *
     * @return $this
     */
    public function setProductIds(array $value);

    /**
     * @return mixed|Collection
     */
    public function getRelatedProducts();
}
