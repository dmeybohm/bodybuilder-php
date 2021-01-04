<?php

namespace Best\ElasticSearch\BodyBuilder;

trait AggregationBuilderTrait
{
    abstract protected function pushQuery(array &$existing, $boolKey, $isInFilterContext, $type, ...$args);

    abstract protected function toBool(array $filters);

    protected $aggregations = [];

    private function makeAggregation($type, $field, ...$args)
    {
        $aggName = '';
        $opts = [];
        $nestedClause = [];

        if (count($args) > 0) {
            foreach ($args as $arg) {
                if (is_string($arg)) {
                    $aggName = $arg;
                    continue;
                }

                if (is_array($arg)) {
                    $opts = array_merge($opts, $arg);
                    continue;
                }

                if (is_callable(end($args))) {
                    $nestedCallback = array_pop($args);
                    $nestedInstance = new BodyBuilder();
                    $nestedResult = $nestedCallback($nestedInstance);

                    $nestedResult = $nestedResult === null ? $nestedInstance : $nestedResult;
                    if (! $nestedResult instanceof BodyBuilder) {
                        throw new \RuntimeException('Invalid class returned from callback');
                    }

                    if ($nestedResult->hasFilter()) {
                        $nestedClause['filter'] = $nestedResult->getFilter();
                    }

                    if ($nestedResult->hasAggregations()) {
                        $nestedClause['aggs'] = $nestedResult->getAggregations();
                    }
                    continue;
                }
            }
        }

        $innerClause = [
            $type => $this->buildClause($field, null, $opts)
        ];

        $innerClause = array_merge($innerClause, $nestedClause);

        if (empty($aggName)) {
            $aggName = "agg_${type}_${field}";
        }

        $this->aggregations[$aggName] = $innerClause;
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
