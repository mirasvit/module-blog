<?php
namespace Mirasvit\Blog\Model\ResourceModel\Category;

use Magento\Eav\Model\Entity\Collection\AbstractCollection;
use Magento\Framework\App\ObjectManager;

class Collection extends AbstractCollection
{
    /**
     * @var bool
     */
    protected $fromRoot = true;

    /**
     * {@inheritdoc}
     */
    protected function _construct()
    {
        $this->_init('Mirasvit\Blog\Model\Category', 'Mirasvit\Blog\Model\ResourceModel\Category');
    }

    /**
     * @return $this
     */
    public function addNameToSelect()
    {
        return $this->addAttributeToSelect('*');
    }

    /**
     * @return $this
     */
    public function addVisibilityFilter()
    {
        $this->addAttributeToFilter('status', 1);

        return $this;
    }

    /**
     * @return $this
     */
    public function excludeRoot()
    {
        $this->fromRoot = false;
        return $this->addFieldToFilter('entity_id', ['neq' => 1]);
    }

    /**
     * @param int|null $parentId
     * @return \Mirasvit\Blog\Model\Category[]
     */
    public function getTree($parentId = null)
    {
        $list = [];

        if ($parentId == null) {
            $parentId = $this->fromRoot ? 0 : 1;
        }

        $collection = clone $this;
        $collection->addFieldToFilter('parent_id', $parentId)
            ->setOrder('position', 'asc');

        foreach ($collection as $item) {
            $list[$item->getId()] = $item;
            if ($item->getChildrenCount()) {
                $items = $this->getTree($item->getId());
                foreach ($items as $child) {
                    $list[$child->getId()] = $child;
                }
            }
        }

        return $list;
    }

    /**
     * {@inheritdoc}
     */
    protected function _toOptionArray($valueField = 'id', $labelField = 'name', $additional = [])
    {
        $result = [];

        $this->addAttributeToSelect('name');

        foreach ($this->getTree(0) as $item) {
            $result[] = [
                'value' => $item->getId(),
                'label' => str_repeat(' ', $item->getLevel() * 5) . $item->getName()
            ];
        }

        return $result;
    }
}