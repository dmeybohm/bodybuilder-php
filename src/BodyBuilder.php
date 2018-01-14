<?php

namespace Best\ElasticSearch\BodyBuilder;

class BodyBuilder
{
    use QueryBuilderTrait;

    /**
     * Create a new BodyBuilder.
     *
     * @return static
     */
    public static function create()
    {
        return new static();
    }

}