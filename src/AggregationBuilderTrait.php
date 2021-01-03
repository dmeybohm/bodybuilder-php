<?php

namespace Best\ElasticSearch\BodyBuilder;

trait AggregationBuilderTrait
{
    abstract protected function pushQuery(array &$existing, $boolKey, $isInFilterContext, $type, ...$args);

    abstract protected function toBool(array $filters);

    protected $aggregations = [];

    private function makeAggregation($type, $field, ...$args)
    {
    }

    public function aggregation(...$args)
    {
        $this->makeAggregation(...$args);

        return $this;
    }

    public function agg(...$args)
    {
        return $this->aggregation(...$args);
    }

    public function getAggregations()
    {
        return $this->aggregations;
    }

    public function hasAggregations()
    {
        return ! empty($this->aggregations);
    }

    public function getRawAggregations()
    {
        return $this->aggregations;
    }
}
