<?php

namespace Best\ElasticSearch\BodyBuilder;

class BodyBuilder
{
    const VERSION_1 = 'v1';
    const VERSION_2 = 'v2';

    use QueryBuilderTrait, FilterBuilderTrait, UtilTrait;

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
     */
    public function build($version = self::VERSION_2)
    {
        $queries = $this->getQuery();
        $filters = $this->getFilter();

        if ($version === 'v2') {
            return $this->doBuild($queries, $filters);
        } elseif ($version === 'v1') {
            return $this->doBuildV1($queries, $filters);
        } else {
            throw new \RuntimeException("Unsupported version");
        }
    }

    private function doBuild(array $queries, array $filters)
    {
        $result = [];

        if (!empty($filters)) {
            $filterBody = $queryBody = [];
            $filterBody['query']['bool']['filter'] = $filters;
            if (!empty($queries['bool'])) {
                $queryBody['query']['bool'] = $queries['bool'];
            } elseif (!empty($queries)) {
                $queryBody['query']['bool']['must'] = $queries;
            }
            $result = array_merge_recursive($result, $filterBody, $queryBody);
        } elseif (!empty($queries)) {
            $result['query'] = $queries;
        }

        return $result;
    }

    private function doBuildV1(array $queries, array $filters)
    {
        $result = [];

        if (!empty($filters)) {
            $result['query']['filtered']['filter'] = $filters;

            if (!empty($queries)) {
                $result['query']['filtered']['query'] = $queries;
            }
        } elseif (!empty($queries)) {
            $result['query'] = $queries;
        }

        return $result;
    }
}