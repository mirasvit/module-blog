# How to upgrade extension

To upgrade the extension follow next steps:

1. Backup your store's database and web directory.
1. Login to the SSH console of your server and navigate to the root directory of the Magento 2 store.
1. Run command `composer require mirasvit/module-blog:* --update-with-dependencies` to update current extension with all dependencies.
   %%% note
   In some cases the command above is not applicable, it's not possible to update just current module, or you just need to upgrade all Mirasvit modules in a bundle. In this case command above will have no effect. <br><br>
   Run instead `composer update mirasvit/*` command. It will update all Mirasvit modules, installed on your store. <br><br>
   %%%
1. Run command `php -f bin/magento setup:upgrade` to install updates.
1. Run command `php -f bin/magento cache:clean` to clean the cache.
1. Deploy static view files

    `rm -rf pub/static/*; rm -rf var/view_preprocessed/*;
      php -f bin/magento setup:static-content:deploy`
    
    
