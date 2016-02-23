<?php

namespace Mirasvit\Blog\Setup;

use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Mirasvit\Blog\Setup\InstallData\PostSetupFactory;
use Mirasvit\Blog\Setup\InstallData\CategorySetupFactory;

class InstallData implements InstallDataInterface
{
    public function __construct(
        PostSetupFactory $postSetupFactory,
        CategorySetupFactory $categorySetupFactory
    ) {
        $this->postSetupFactory = $postSetupFactory;
        $this->categorySetupFactory = $categorySetupFactory;
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
    }
}