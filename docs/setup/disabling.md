# Disabling Extension

## Temporarily Disable

To temporarily disable the extension please follow these steps:

1. Login to the SSH console on your server and navigate to the root directory of the Magento 2 store.
1. Run the command `php -f bin/magento module:disable Mirasvit_Blog` to disable the extension.
1. Login to the Magento back-end and purge **ALL** store cache (if enabled).

## Extension Removing

To uninstall the extension please follow these steps:

1. Login to the SSH console on your server and navigate to the root directory of the Magento 2 store.
1. Run the command `composer remove mirasvit/module-blog` to remove the extension.
1. Login to the Magento back-end and purge **ALL** store cache (if enabled).
