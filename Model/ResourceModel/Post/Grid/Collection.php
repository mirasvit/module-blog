<?php

namespace Mirasvit\Blog\Model\ResourceModel\Post\Grid;

use Magento\Eav\Model\Entity\Attribute\AttributeInterface;
use Magento\Framework\Api\Search\SearchResultInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\DB\Select;
use Magento\Framework\Search\AggregationInterface;
use Mirasvit\Blog\Model\ResourceModel\Post\Collection as PostCollection;

class Collection extends PostCollection implements SearchResultInterface
{
    const CAT_PROD_LINK_ALIAS = 'category_ids_table';
    const CAT_PROD_LINK       = 'mst_blog_category_post';

    /**
     * {@inheritdoc}
     */
    public function getAggregations()
    {
        return $this->aggregations;
    }

    /**
     * {@inheritdoc}
     */
    public function setAggregations($aggregations)
    {
        $this->aggregations = $aggregations;
    }

    /**
     * {@inheritdoc}
     */
    public function getAllIds($limit = null, $offset = null)
    {
        return $this->getConnection()->fetchCol($this->_getAllIdsSelect($limit, $offset), $this->_bindParams);
    }

    /**
     * {@inheritdoc}
     */
    public function getSearchCriteria()
    {
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function setSearchCriteria(SearchCriteriaInterface $searchCriteria = null)
    {
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getTotalCount()
    {
        return $this->getSize();
    }

    /**
     * {@inheritdoc}
     */
    public function setTotalCount($totalCount)
    {
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setItems(array $items = null)
    {
        return $this;
    }

    /**
     * Overrides the basic implementation of this to add special handling for the `category_ids`
     * column. This adds the category ids filter to be used in the Magento admin and joins that
     * table in (grouping by the post entity_id column).
     *
     * @param array|int|AttributeInterface|string $attribute
     * @param null                                $condition
     * @param string                              $joinType
     *
     * @return $this
     */
    public function addAttributeToFilter($attribute, $condition = null, $joinType = 'inner')
    {
        $select = $this->getSelect();

        if ($attribute !== "category_ids") {
            return parent::addAttributeToFilter($attribute, $condition, $joinType);
        }

        if (isset($select->getPart($select::FROM)[self::CAT_PROD_LINK_ALIAS])) {
            return $this;
        }

        $this->joinCategoryIdsTable($select);
        $this->addConditionToSelect($select, $condition);

        return $this;
    }

    /**
     * Joins the category / post linking table into this queyr.
     *
     * @param Select $select
     *
     * @return void
     */
    private function joinCategoryIdsTable(Select $select)
    {
        $select->group('entity_id');
        $select->joinInner(
            [self::CAT_PROD_LINK_ALIAS => $this->getTable(self::CAT_PROD_LINK)],
            'e.entity_id = ' . self::CAT_PROD_LINK_ALIAS . '.post_id',
            'category_id'
        );
    }

    /**
     * Adds the condition relating to category ids.
     *
     * @param Select $select
     * @param null                         $condition
     *
     * @return void
     */
    private function addConditionToSelect(Select $select, $condition = null)
    {
        $select->where($this->_getConditionSql(self::CAT_PROD_LINK_ALIAS . '.category_id', $condition));
    }
}
