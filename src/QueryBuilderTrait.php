<?php

namespace Best\ElasticSearch\BodyBuilder;

trait QueryBuilderTrait
{
    abstract protected function pushQuery(array &$existing, $boolKey, $isInFilterContext, $type, ...$args);
    abstract protected function toBool(array $filters);

    /**
     * Arrays of query parameters.
     *
     * @var array[]
     */
    protected $query = [
        'and' => [],
        'or' => [],
        'not' => []
    ];

    /**
     * Add a query.
     *
     * @param array ...$args
     * @return $this
     */
    public function query(...$args)
    {
        return $this->makeQuery('and', ...$args);
    }

    /**
     * Add an 'and' query.
     *
     * @return $this
     */
    public function andQuery(...$args)
    {
        return $this->query(...$args);
    }

    /**
     * Add an 'or' query.
     *
     * @param array ...$args
     * @return $this
     */
    public function orQuery(...$args)
    {
        return $this->makeQuery('or', ...$args);
    }

    /**
     * Get the query for this object.
     *
     * @return array
     */
    public function getQuery()
    {
        return $this->hasQuery() ? $this->toBool($this->query) : [];
    }

    /**
     * Whether the object has a query.
     *
     * @return boolean
     */
    public function hasQuery()
    {
        return !empty($this->query['and']) || !empty($this->query['or']) || !empty($this->query['not']);
    }

    /**
     * Set the `minimum_should_match` property on a bool query with more than
     * one `should` clause.
     *
     * @param  mixed $param  minimum_should_match parameter. For possible values
     *                       see https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-minimum-should-match.html
     * @return $this BodyBuilder.
     */
    public function queryMinimumShouldMatch($param)
    {
        $this->addMinimumShouldMatch($param);
        return $this;
    }

    /**
     * @param string $type
     * @param mixed ...$args
     * @return $this
     */
    private function makeQuery($type, ...$args)
    {
        $isInFilterContext = isset($this->options['isInFilterContext']) ?
            $this->options['isInFilterContext'] : false;
        $this->pushQuery($this->query, $type, $isInFilterContext, ...$args);
        return $this;
    }

    /**
     * Add minimum should match.
     *
     * @param mixed $value
     * @return void
     */
    private function addMinimumShouldMatch($value)
    {
        $this->query['minimum_should_match'] = $value;
    }
}
