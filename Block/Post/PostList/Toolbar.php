<?php

namespace Mirasvit\Blog\Block\Post\PostList;

use Magento\Catalog\Model\Session;
use Magento\Framework\DataObject;
use Magento\Framework\Registry;
use Magento\Framework\Url\EncoderInterface;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Magento\Store\Model\ScopeInterface;
use Mirasvit\Blog\Block\Html\Pager;
use Mirasvit\Blog\Model\Author;
use Mirasvit\Blog\Model\Category;
use Mirasvit\Blog\Model\Config;
use Mirasvit\Blog\Model\Post\PostList\Toolbar as ToolbarModel;
use Mirasvit\Blog\Model\ResourceModel\Post\Collection;
use Mirasvit\Blog\Model\Tag;
use Mirasvit\Blog\Model\UrlInterface;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class Toolbar extends Template
{
    /**
     * @var Collection
     */
    protected $collection;

    /**
     * List of available order fields.
     * @var array
     */
    protected $availableOrder = null;

    /**
     * Default Order field.
     * @var string
     */
    protected $orderField = null;

    /**
     * Default direction.
     * @var string
     */
    protected $direction = 'desc';

    /**
     * @var bool
     */
    protected $paramsMemorizeAllowed = true;

    /**
     * Catalog session.
     * @var Session
     */
    protected $session;

    /**
     * @var ToolbarModel
     */
    protected $toolbarModel;

    /**
     * @var EncoderInterface
     */
    protected $urlEncoder;

    /**
     * @var Config
     */
    protected $config;

    /**
     * @var Registry
     */
    protected $registry;

    /**
     * @param Context          $context
     * @param Session          $session
     * @param ToolbarModel     $toolbarModel
     * @param EncoderInterface $urlEncoder
     * @param Config           $config
     * @param Registry         $registry
     */
    public function __construct(
        Context $context,
        Session $session,
        ToolbarModel $toolbarModel,
        EncoderInterface $urlEncoder,
        Config $config,
        Registry $registry
    ) {
        $this->session      = $session;
        $this->toolbarModel = $toolbarModel;
        $this->urlEncoder   = $urlEncoder;
        $this->config       = $config;
        $this->registry     = $registry;

        parent::__construct($context);
    }

    /**
     * Disable list state params memorizing.
     * @return $this
     */
    public function disableParamsMemorizing()
    {
        $this->paramsMemorizeAllowed = false;

        return $this;
    }

    /**
     * Set default Order field.
     *
     * @param string $field
     *
     * @return $this
     */
    public function setDefaultOrder($field)
    {
        $this->loadAvailableOrders();
        if (isset($this->availableOrder[$field])) {
            $this->orderField = $field;
        }

        return $this;
    }

    /**
     * Set default sort direction.
     *
     * @param string $dir
     *
     * @return $this
     */
    public function setDefaultDirection($dir)
    {
        if (in_array(strtolower($dir), ['asc', 'desc'])) {
            $this->direction = strtolower($dir);
        }

        return $this;
    }

    /**
     * Set Available order fields list.
     *
     * @param array $orders
     *
     * @return $this
     */
    public function setAvailableOrders($orders)
    {
        $this->availableOrder = $orders;

        return $this;
    }

    /**
     * Add order to available orders.
     *
     * @param string $order
     * @param string $value
     *
     * @return $this
     */
    public function addOrderToAvailableOrders($order, $value)
    {
        $this->loadAvailableOrders();
        $this->availableOrder[$order] = $value;

        return $this;
    }

    /**
     * Remove order from available orders if exists.
     *
     * @param string $order
     *
     * @return $this
     */
    public function removeOrderFromAvailableOrders($order)
    {
        $this->loadAvailableOrders();

        if (isset($this->availableOrder[$order])) {
            unset($this->availableOrder[$order]);
        }

        return $this;
    }

    /**
     * Compare defined order field with current order field.
     *
     * @param string $order
     *
     * @return bool
     */
    public function isOrderCurrent($order)
    {
        return $order == $this->getCurrentOrder();
    }

    /**
     * @param array $params
     *
     * @return string
     */
    public function getPagerEncodedUrl($params = [])
    {
        return $this->urlEncoder->encode($this->getPagerUrl($params));
    }

    /**
     * Return current URL with rewrites and additional parameters.
     *
     * @param array $params Query parameters.
     *
     * @return string
     */
    public function getPagerUrl($params = [])
    {
        $urlParams                 = [];
        $urlParams['_current']     = true;
        $urlParams['_escape']      = true;
        $urlParams['_use_rewrite'] = true;
        $urlParams['_query']       = $params;

        return $this->getUrl('*/*/*', $urlParams);
    }

    /**
     * @param int $limit
     *
     * @return bool
     */
    public function isLimitCurrent($limit)
    {
        return $limit == $this->getLimit();
    }

    /**
     * @return int
     */
    public function getFirstNum()
    {
        $collection = $this->getCollection();

        return $collection->getPageSize() * ($collection->getCurPage() - 1) + 1;
    }

    /**
     * @return Collection
     */
    public function getCollection()
    {
        return $this->collection;
    }

    /**
     * Set collection to pager.
     *
     * @param Collection $collection
     *
     * @return $this
     */
    public function setCollection($collection)
    {
        $this->collection = $collection;

        $this->collection->setCurPage($this->getCurrentPage());

        // we need to set pagination only if passed value integer and more that 0
        $limit = (int)$this->getLimit();
        if ($limit) {
            $this->collection->setPageSize($limit);
        }
        if ($this->getCurrentOrder()) {
            $this->collection->setOrder($this->getCurrentOrder(), $this->getCurrentDirection());
        }

        return $this;
    }

    /**
     * Return current page from request.
     * @return int
     */
    public function getCurrentPage()
    {
        return $this->toolbarModel->getCurrentPage();
    }

    /**
     * @return int
     */
    public function getLimit()
    {
        $limit = $this->_getData('blog_current_limit');
        if ($limit) {
            return $limit;
        }

        $limits       = $this->getAvailableLimit();
        $defaultLimit = $this->getDefaultPerPageValue();
        if (!$defaultLimit || !isset($limits[$defaultLimit])) {
            $keys         = array_keys($limits);
            $defaultLimit = $keys[0];
        }

        $limit = $this->toolbarModel->getLimit();
        if (!$limit || !isset($limits[$limit])) {
            $limit = $defaultLimit;
        }

        if ($limit != $defaultLimit) {
            $this->memorizeParam('limit_page', $limit);
        }

        $this->setData('blog_current_limit', $limit);

        return $limit;
    }

    /**
     * @return array
     */
    public function getAvailableLimit()
    {
        return [10 => 10, 20 => 20, 50 => 50];
    }

    /**
     * Retrieve default per page values.
     * @return string (comma separated)
     */
    public function getDefaultPerPageValue()
    {
        if ($default = $this->getDefaultListPerPage()) {
            return $default;
        }

        return 10;
    }

    /**
     * Memorize parameter value for session.
     *
     * @param string $param Parameter name.
     * @param string $value Parameter value.
     *
     * @return $this
     */
    protected function memorizeParam($param, $value)
    {
        if ($this->paramsMemorizeAllowed && !$this->session->getParamsMemorizeDisabled()) {
            $this->session->setData($param, $value);
        }

        return $this;
    }

    /**
     * Get grit products sort order field.
     * @return string
     */
    public function getCurrentOrder()
    {
        $order = $this->_getData('blog_current_order');
        if ($order) {
            return $order;
        }

        $orders       = $this->getAvailableOrders();
        $defaultOrder = $this->getOrderField();

        if (!isset($orders[$defaultOrder])) {
            $keys         = array_keys($orders);
            $defaultOrder = $keys[0];
        }

        $order = $this->toolbarModel->getOrder();
        if (!$order || !isset($orders[$order])) {
            $order = $defaultOrder;
        }

        if ($order != $defaultOrder) {
            $this->memorizeParam('sort_order', $order);
        }

        $this->setData('blog_current_order', $order);

        return $order;
    }

    /**
     * Retrieve available Order fields list.
     * @return array
     */
    public function getAvailableOrders()
    {
        $this->loadAvailableOrders();

        return $this->availableOrder;
    }

    /**
     * @return $this
     */
    private function loadAvailableOrders()
    {
        if ($this->availableOrder === null) {
            $this->availableOrder = [
                'created_at' => __('Date'),
                'name'       => __('Name'),
            ];
        }

        return $this;
    }

    /**
     * @return string
     */
    protected function getOrderField()
    {
        if ($this->orderField === null) {
            $this->orderField = $this->config->getDefaultSortField();
        }

        return $this->orderField;
    }

    /**
     * Retrieve current direction.
     * @return string
     */
    public function getCurrentDirection()
    {
        $dir = $this->_getData('blog_current_direction');
        if ($dir) {
            return $dir;
        }

        $directions = ['asc', 'desc'];
        $dir        = strtolower($this->toolbarModel->getDirection());
        if (!$dir || !in_array($dir, $directions)) {
            $dir = $this->direction;
        }

        if ($dir != $this->direction) {
            $this->memorizeParam('sort_direction', $dir);
        }

        $this->setData('blog_current_direction', $dir);

        return $dir;
    }

    /**
     * @return int
     */
    public function getLastNum()
    {
        $collection = $this->getCollection();

        return $collection->getPageSize() * ($collection->getCurPage() - 1) + $collection->count();
    }

    /**
     * @return int
     */
    public function getTotalNum()
    {
        return $this->getCollection()->getSize();
    }

    /**
     * @return bool
     */
    public function isFirstPage()
    {
        return $this->getCollection()->getCurPage() == 1;
    }

    /**
     * @return int
     */
    public function getLastPageNum()
    {
        return $this->getCollection()->getLastPageNumber();
    }

    /**
     * @return string
     */
    public function getPagerHtml()
    {
        /** @var Pager $pagerBlock */
        $pagerBlock = $this->getChildBlock('pager');
        if ($this->getEntity()) {
            $pagerBlock->setEntity($this->getEntity());
        }

        if ($pagerBlock instanceof DataObject) {
            $pagerBlock->setAvailableLimit($this->getAvailableLimit());

            $pagerBlock->setUseContainer(false)
                ->setShowPerPage(true)
                ->setShowAmounts(false)
                ->setFrameLength($this->_scopeConfig->getValue(
                    'design/pagination/pagination_frame',
                    ScopeInterface::SCOPE_STORE
                ))->setJump($this->_scopeConfig->getValue(
                    'design/pagination/pagination_frame_skip',
                    ScopeInterface::SCOPE_STORE
                ))->setLimit($this->getLimit())
                ->setCollection($this->getCollection());

            return $pagerBlock->toHtml();
        }

        return '';
    }

    /**
     * @return UrlInterface|null
     */
    public function getEntity()
    {
        $entity = null;
        if ($this->getCategory()) {
            $entity = $this->getCategory();
        } elseif ($this->getTag()) {
            $entity = $this->getTag();
        } elseif ($this->getAuthor()) {
            $entity = $this->getAuthor();
        }

        return $entity;
    }

    /**
     * @return Category
     */
    public function getCategory()
    {
        return $this->registry->registry('current_blog_category');
    }

    /**
     * @return Tag
     */
    public function getTag()
    {
        return $this->registry->registry('current_blog_tag');
    }

    /**
     * @return Author
     */
    public function getAuthor()
    {
        return $this->registry->registry('current_blog_author');
    }
}
