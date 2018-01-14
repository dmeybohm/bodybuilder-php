<?php

namespace Best\ElasticSearch\BodyBuilder;

class FilterBuilder
{
    use FilterBuilderTrait;

    /**
     * @param array $options
     */
    public function __construct(array $options)
    {
        $this->options = $options;
    }
}