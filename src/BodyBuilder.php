<?php

namespace Best\ElasticSearch\BodyBuilder;

class BodyBuilder
{
    use QueryBuilderTrait, FilterBuilderTrait, UtilTrait;

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