<?php

namespace Best\ElasticSearch\BodyBuilder;

trait QueryBuilderTrait
{
    use UtilTrait;

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
     * Add minimum should match.
     *
     * @param string $str
     * @return void
     */
    protected function addMinimumShouldMatch($str)
    {
        $query['minimum_should_match'] = $str;
    }

    /**
     * @param array ...$args
     * @return $this
     */
    public function query(...$args)
    {
        $this->makeQuery('and', ...$args);
        return $this;
    }

    public function getQuery()
    {
        return $this->hasQuery() ? $this->toBool($this->query) : [];
    }

    public function hasQuery()
    {
        return !empty($this->query['and']) || !empty($this->query['or']) || !empty($this->query['not']);
    }

    protected function makeQuery($type, ...$args)
    {
       $this->pushQuery($this->query, $type, ...$args);
    }
}