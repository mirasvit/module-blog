<?php

namespace Mirasvit\Blog\Model;

interface UrlInterface
{
    /**
     * @param array $urlParams
     *
     * @return string
     */
    public function getUrl($urlParams = []);
}
