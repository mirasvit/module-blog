<?php

namespace Mirasvit\Blog\Block\Post;

class Meta extends AbstractBlock
{
    /**
     * @return \Mirasvit\Blog\Model\Post
     */
    public function getPost()
    {
        if ($this->hasData('post')) {
            return $this->getData('post');
        }

        return $this->registry->registry('current_blog_post');
    }

    /**
     * @return \Mirasvit\Blog\Model\Category
     */
    public function getCategory()
    {
        return $this->registry->registry('current_blog_category');
    }

    /**
     * @return string
     */
    public function getCommentProvider()
    {
        return $this->config->getCommentProvider();
    }

    /**
     * @return string
     */
    public function getDisqusShortname()
    {
        return $this->config->getDisqusShortname();
    }

    /**
     * @param string $date
     * @return string
     */
    public function toDateFormat($date)
    {
        return date($this->config->getDateFormat(), strtotime($date));
    }

    /**
     * @return bool
     */
    public function isAddThisEnabled()
    {
        return $this->config->isAddThisEnabled();
    }
}
