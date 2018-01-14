<?php

namespace Best\ElasticSearch\BodyBuilder;

trait FilterBuilderTrait
{
    public function hasFilter()
    {
        return false;
    }

    public function getFilter()
    {
        return [];
    }

    public function filter(...$args)
    {
        return $this;
    }
}