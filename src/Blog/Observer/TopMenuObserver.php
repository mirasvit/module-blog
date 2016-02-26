<?php
namespace Mirasvit\Blog\Observer;

use Magento\Framework\Event\Observer as EventObserver;
use Magento\Framework\Event\ObserverInterface;
use Mirasvit\Blog\Model\ResourceModel\Category\CollectionFactory as CategoryCollectionFactory;
use Magento\Framework\Data\Tree\Node as TreeNode;

class TopMenuObserver implements ObserverInterface
{
    /**
     * @var CategoryCollectionFactory
     */
    protected $categoryCollectionFactory;

    /**
     * @param CategoryCollectionFactory $categoryCollectionFactory
     */
    public function __construct(
        CategoryCollectionFactory $categoryCollectionFactory
    ) {
        $this->categoryCollectionFactory = $categoryCollectionFactory;
    }

    /**
     * {@inheritdoc}
     *
     * @param EventObserver $observer
     */
    public function execute(EventObserver $observer)
    {
        /** @var \Magento\Framework\Data\Tree\Node $menu */
        $menu = $observer->getData('menu');

        $categories = $this->categoryCollectionFactory->create()
            ->addAttributeToSelect('*')
            ->addVisibilityFilter();

        $tree = $categories->getTree();

        foreach ($tree as $category) {
            if (isset($tree[$category->getParentId()])) {
                $parentNode = $tree[$category->getParentId()]->getData('node');
            } else {
                $parentNode = null;
            }

            $node = new TreeNode(
                [
                    'id'   => 'blog-node-' . $category->getId(),
                    'name' => $category->getName(),
                    'url'  => $category->getUrl()
                ],
                'id',
                $menu->getTree(),
                $parentNode
            );

            if ($parentNode) {
                $parentNode->addChild($node);
            } else {
                $menu->addChild($node);
            }

            $category->setData('node', $node);
        }
    }
}
