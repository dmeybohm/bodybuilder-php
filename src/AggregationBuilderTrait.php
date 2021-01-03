<?php

namespace Best\ElasticSearch\BodyBuilder;

trait AggregationBuilderTrait
{
    abstract protected function pushQuery(array &$existing, $boolKey, $isInFilterContext, $type, ...$args);

    abstract protected function toBool(array $filters);

    protected $aggregations = [];

    private function makeAggregation($type, $field, ...$args)
    {
        $name = ! empty($args) && is_string($args[0]) ? $args[0] : "agg_${type}_${field}";

        if (! empty($args)) {
            if (is_string($args[0])) {
                if (is_array($field)) {
                    $this->aggregations[$name] = [
                        $type => $field
                    ];
                } else {
                    $this->aggregations[$name] = [
                        $type => [
                            'field' => $field
                        ]
                    ];
                }
            } else {
                if (is_callable(end($args))) {
                    // TODO callback handling here
                } else {
                    $test = [
                        'field' => $field
                    ];

                    $test = array_merge($test, $args[0]);

                    $this->aggregations[$name] = [
                        $type => $test
                    ];
                }
            }
        } else {
            $this->aggregations[$name] = [
                $type => [
                    'field' => $field
                ]
            ];
        }
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
