# Blog MX | Magento 2 Blog Module by [Mirasvit](https://mirasvit.com/)

FREE, fully featured, powerful Blog solution for your online store! Magento 2 Blog MX allows you to open a blog and engage more and more customers to your shop activities using any type of content: images, video, articles etc.

## Key Features

* SEO friendly posts and URLs
* Multi-level categories
* Ability to preview post before publication or before save changes
* RSS Feed
* Tags and Tag Cloud
* Disqus comments
* Featured image for posts
* Ability to pin post at the top
* Sharing buttons

[more information](https://mirasvit.com/magento-2-extensions/blog.html)


## Installation

Log in to the Magento server, go to your Magento install dir and run these commands:
```
composer config repositories.mirasvit-blog vcs https://github.com/mirasvit/module-blog
composer require mirasvit/module-blog:dev-master

bin/magento module:enable Mirasvit_Blog
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
