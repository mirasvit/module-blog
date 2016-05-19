<?php

namespace Mirasvit\Blog\Setup;

use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Mirasvit\Blog\Setup\InstallData\PostSetupFactory;
use Mirasvit\Blog\Setup\InstallData\CategorySetupFactory;
use Mirasvit\Blog\Model\CategoryFactory;

class InstallData implements InstallDataInterface
{
    /**
     * @param PostSetupFactory     $postSetupFactory
     * @param CategorySetupFactory $categorySetupFactory
     * @param CategoryFactory      $categoryFactory
     */
    public function __construct(
        PostSetupFactory $postSetupFactory,
        CategorySetupFactory $categorySetupFactory,
        CategoryFactory $categoryFactory
    ) {
        $this->postSetupFactory = $postSetupFactory;
        $this->categorySetupFactory = $categorySetupFactory;
        $this->categoryFactory = $categoryFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $postSetup = $this->postSetupFactory->create(['setup' => $setup]);
        $postSetup->installEntities();

        $categorySetup = $this->categorySetupFactory->create(['setup' => $setup]);
        $categorySetup->installEntities();

        $category = $this->categoryFactory->create();
        $category
            ->setName(__('Blog'))
            ->setParentId(null)
            ->setPath('1')
            ->setLevel(0)
            ->setPosition(0)
            ->setStatus(1)
            ->save();
    }
}