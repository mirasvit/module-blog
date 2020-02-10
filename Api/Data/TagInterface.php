<?php

namespace Mirasvit\Blog\Api\Data;

interface TagInterface
{
    const ID = 'tag_id';

    const TABLE = 'mst_blog_tag';

    const URL_KEY = 'url_key';
    const NAME    = 'name';


    /**
     * @return int
     */
    public function getId();

    /**
     * @return string
     */
    public function getUrlKey();

    /**
     * @param string $value
     *
     * @return $this
     */
    public function setUrlKey($value);

    /**
     * @return string
     */
    public function getName();

    /**
     * @param string $value
     *
     * @return $this
     */
    public function setName($value);
}
