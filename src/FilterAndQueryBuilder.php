<?php

namespace Best\ElasticSearch\BodyBuilder;

class FilterAndQueryBuilder
{
    use FilterBuilderTrait, QueryBuilderTrait, UtilTrait;

    /**
     * @param array $options
     */
    public function __construct(array $options)
    {
        $this->options = $options;
    }
}