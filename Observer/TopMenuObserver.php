<?php

namespace Mirasvit\Blog\Observer;

use Magento\Framework\Data\Tree\Node as TreeNode;
use Magento\Framework\Event\Observer as EventObserver;
use Magento\Framework\Event\ObserverInterface;
use Mirasvit\Blog\Model\Config;
use Mirasvit\Blog\Model\ResourceModel\Category\CollectionFactory as CategoryCollectionFactory;

class TopMenuObserver implements ObserverInterface
{
    /**
     * @var CategoryCollectionFactory
     */
    protected $categoryCollectionFactory;

    /**
     * @var Config
     */
    protected $config;

    /**
     * @param Config                    $config
     * @param CategoryCollectionFactory $categoryCollectionFactory
     */
    public function __construct(
        Config $config,
        CategoryCollectionFactory $categoryCollectionFactory
    ) {
        $this->config                    = $config;
        $this->categoryCollectionFactory = $categoryCollectionFactory;
    }

    /**
     * {@inheritdoc}
     * @param EventObserver $observer
     */
    public function execute(EventObserver $observer)
    {
        if (!$this->config->isDisplayInMenu()) {
            return;
        }
        /** @var TreeNode $menu */
        $menu = $observer->getData('menu');

        $categories = $this->categoryCollectionFactory->create()
            ->addAttributeToSelect(['name', 'url_key'])
            ->excludeRoot()
            ->addVisibilityFilter();

        $tree = $categories->getTree();

        $rootNode = new TreeNode(
            [
                'id'   => 'blog-node-root',
                'name' => $this->config->getMenuTitle(),
                'url'  => $this->config->getBaseUrl(),
            ],
            'id',
            $menu->getTree(),
            null
        );
        //@todo find correct way to add class
        if ($menu->getPositionClass()) {
            $menu->setPositionClass('blog-mx' . $menu->getPositionClass());
        } else {
            $menu->setPositionClass('blog-mx nav' . $menu->getPositionClass());
        }
        $menu->addChild($rootNode);

        foreach ($tree as $category) {
            if (isset($tree[$category->getParentId()])) {
                $parentNode = $tree[$category->getParentId()]->getData('node');
            } else {
                $parentNode = $rootNode;
            }

            $node = new TreeNode(
                [
                    'id'   => 'blog-node-' . $category->getId(),
                    'name' => $category->getName(),
                    'url'  => $category->getUrl(),
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
