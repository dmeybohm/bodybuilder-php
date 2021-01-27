<?php

namespace Best\ElasticSearch\BodyBuilder;

class BodyBuilder
{
    const VERSION_1 = 'v1';
    const VERSION_2 = 'v2';

    use QueryBuilderTrait, FilterBuilderTrait, AggregationBuilderTrait, UtilTrait;

    /**
     * Body that gets returned.
     *
     * @var array
     */
    private $body = [];

    /**
     * Create a new BodyBuilder.
     *
     * @return static
     */
    public static function create()
    {
        return new static();
    }

    /**
     * Build an Elasticsearch array.
     *
     * @return array
     */
    public function build($version = self::VERSION_2)
    {
        $queries = $this->getQuery();
        $filters = $this->getFilter();
        $aggregations = $this->getAggregations();

        if ($version === 'v2') {
            return $this->doBuild($queries, $filters, $aggregations);
        } elseif ($version === 'v1') {
            return $this->doBuildV1($queries, $filters, $aggregations);
        } else {
            throw new \RuntimeException('Unsupported version');
        }
    }

    /**
     * Set a sort direction on a given field.
     *
     * @param  string $field             Field name.
     * @param  string $direction='asc' A valid direction: 'asc' or 'desc'.
     * @returns $this Builder.
     */
    public function sort($field, $direction = 'asc')
    {
        $this->body['sort'] = empty($this->body['sort']) ? [] : $this->body['sort'];
        if (is_array($field)) {
            foreach ($field as $sorts) {
                foreach ($sorts as $key => $value) {
                    $this->sortMerge($this->body['sort'], $key, $value);
                }
            }
        } else {
            $this->sortMerge($this->body['sort'], $field, $direction);
        }

        return $this;
    }

    /**
     * Set the from.
     *
     * @param integer $from
     * @return $this
     */
    public function from($from)
    {
        $this->body['from'] = $from;

        return $this;
    }

    /**
     * Set the size.
     *
     * @param integer $size
     * @return $this
     */
    public function size($size)
    {
        $this->body['size'] = $size;

        return $this;
    }

    /**
     * Set a raw option.
     *
     * @param mixed $option
     * @return $this
     */
    public function rawOption($key, $value)
    {
        $this->body[$key] = $value;

        return $this;
    }

    private function doBuild(array $queries, array $filters, array $aggregations)
    {
        $result = $this->body;

        if (! empty($filters)) {
            $filterBody = $queryBody = [];
            $filterBody['query']['bool']['filter'] = $filters;
            if (! empty($queries['bool'])) {
                $queryBody['query']['bool'] = $queries['bool'];
            } elseif (! empty($queries)) {
                $queryBody['query']['bool']['must'] = $queries;
            }
            $result = array_merge_recursive($result, $filterBody, $queryBody);
        } elseif (! empty($queries)) {
            $result['query'] = $queries;
        }

        if (! empty($aggregations)) {
            $result['aggs'] = $aggregations;
        }

        return $result;
    }

    private function doBuildV1(array $queries, array $filters, array $aggregations)
    {
        $result = $this->body;

        if (! empty($filters)) {
            $result['query']['filtered']['filter'] = $filters;

            if (! empty($queries)) {
                $result['query']['filtered']['query'] = $queries;
            }
        } elseif (! empty($queries)) {
            $result['query'] = $queries;
        }

        if (! empty($aggregations)) {
            $result['aggregations'] = $aggregations;
        }

        return $result;
    }
}
