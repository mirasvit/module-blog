<?php

namespace Mirasvit\Blog\Model;

class Config
{
    /**
     * @return string
     */
    public function getDefaultSortField()
    {
        return 'created_at';
    }
}