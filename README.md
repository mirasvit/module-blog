# Blog MX | Magento 2 Blog Module by [Mirasvit](https://mirasvit.com/)

## Key Features

* SEO friendly URLs
* Multi-level categories
* Ability to preview post before publication or before save changes
* RSS Feed
* Tags
* Disqus comments
* Featured image
* Ability to pin post at the top
* Sharing buttons

## Installation

Log in to the Magento server, go to your Magento install dir and run these commands:
```
composer config repositories.mirasvit-blog vcs https://github.com/mirasvit/module-blog
composer require mirasvit/module-blog:dev-master

bin/magento setup:upgrade

rm -rf pub/static/*; rm -rf var/view_preprocessed/*;
bin/magento setup:static-content:deploy
```

## Demo
[http://blog.m2.mirasvit.com/blog/fashion/](http://blog.m2.mirasvit.com/blog/fashion/)

## Sample Data
[https://github.com/mirasvit/module-blog-sample-data](https://github.com/mirasvit/module-blog-sample-data)

## Support
[https://mirasvit.com/](https://mirasvit.com/)
