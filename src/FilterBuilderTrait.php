<?php

namespace Best\ElasticSearch\BodyBuilder;

trait FilterBuilderTrait
{
    abstract protected function pushQuery(array &$existing, $boolKey, $isInFilterContext, $type,  ...$args);
    abstract protected function toBool(array $filters);

    /**
     * Filters.
     *
     * @var array[]
     */
    protected $filters = [
        'and' => [],
        'or' => [],
        'not' => [],
    ];

    public function hasFilter()
    {
        return !empty($this->filters['and']) || !empty($this->filters['or']) || !empty($this->filters['not']);
    }

    public function getFilter()
    {
        return $this->hasFilter() ? $this->toBool($this->filters) : [];
    }

    public function filter(...$args)
    {
        return $this->makeFilter('and', ...$args);
    }

    public function andFilter(...$args)
    {
        return $this->filter(...$args);
    }

    public function addFilter(...$args)
    {
        return $this->filter(...$args);
    }

    public function orFilter(...$args)
    {
        return $this->makeFilter('or', ...$args);
    }

    public function notFilter(...$args)
    {
        return $this->makeFilter('not', ...$args);
    }

    private function makeFilter($filterType, ...$args)
    {
        $this->pushQuery($this->filters, $filterType, false, ...$args);
        return $this;
    }
}