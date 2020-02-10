<?php

namespace Mirasvit\Blog\Ui\Component;

use Magento\Customer\Ui\Component\Listing\AttributeRepository;
use Magento\Framework\Api\Filter;
use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\Api\Search\SearchCriteriaBuilder;
use Magento\Framework\Api\Search\SearchResultInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\View\Element\UiComponent\DataProvider\Reporting;
use Mirasvit\Blog\Model\ResourceModel\Post\Collection;

class DataProvider extends \Magento\Framework\View\Element\UiComponent\DataProvider\DataProvider
{
    private $collection;

    /**
     * @var AttributeRepository
     */
    private $attributeRepository;

    /**
     * @param string                $name
     * @param string                $primaryFieldName
     * @param string                $requestFieldName
     * @param Reporting             $reporting
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param RequestInterface      $request
     * @param FilterBuilder         $filterBuilder
     * @param AttributeRepository   $attributeRepository
     * @param array                 $meta
     * @param array                 $data
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        Reporting $reporting,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        RequestInterface $request,
        FilterBuilder $filterBuilder,
        AttributeRepository $attributeRepository,
        array $meta = [],
        array $data = []
    ) {
        $this->attributeRepository = $attributeRepository;

        parent::__construct(
            $name,
            $primaryFieldName,
            $requestFieldName,
            $reporting,
            $searchCriteriaBuilder,
            $request,
            $filterBuilder,
            $meta,
            $data
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getData()
    {
        $collection = $this->getCollection();

        foreach ($collection as $post) {
            $post->setData('category_ids', $post->getCategoryIds());
        }

        $data = $this->searchResultToOutput($collection);

        return $data;
    }

    public function getCollection()
    {
        if (!$this->collection) {
            /** @var Collection $collection */
            $this->collection = $this->getSearchResult();

            $this->collection
                ->addAttributeToSelect([
                    'name',
                    'status',
                    'content',
                    'meta_title',
                    'meta_keywords',
                    'meta_description',
                    'url_key',
                ])
                ->addPostFilter();
        }

        return $this->collection;
    }

    /**
     * @param SearchResultInterface $searchResult
     *
     * @return array
     */
    protected function searchResultToOutput(SearchResultInterface $searchResult)
    {
        $arrItems                 = [];
        $arrItems['totalRecords'] = $searchResult->getTotalCount();

        $arrItems['items'] = [];
        foreach ($searchResult->getItems() as $item) {
            $arrItems['items'][] = $item->getData();
        }

        return $arrItems;
    }

    public function addFilter(Filter $filter)
    {
        if ($filter->getField() === 'fulltext') {
            $collection = $this->getCollection();
            $collection->addFieldToFilter('name', ['like' => '%' . $filter->getValue() . '%']);
        } else {
            return parent::addFilter($filter);
        }
    }
}
