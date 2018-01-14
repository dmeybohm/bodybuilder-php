<?php

namespace Best\ElasticSearch\BodyBuilder;

trait UtilTrait
{
    /**
     * Options
     *
     * @var array
     */
    protected $options = [];

    /**
     * @param array $existing
     * @param $boolKey
     * @param $type
     * @param array ...$args
     * @return void
     */
    protected function pushQuery(array &$existing, $boolKey, $isInFilterContext, $type, ...$args)
    {
        $nested = [];
        if (is_callable(end($args))) {
            $nestedCallback = array_pop($args);
            $nestedInstance = new BodyBuilder();
            $nestedResult = $nestedCallback($nestedInstance);

            // don't require the user to return a result, since PHP doesn't have
            // a concise syntax for anonymous functions, but allow the user to
            // to override the builder that we use:
            $nestedResult = $nestedResult === null ? $nestedInstance : $nestedResult;
            if (!$nestedResult instanceof BodyBuilder) {
                throw new \RuntimeException("Invalid class returned from callback");
            }

            if ($nestedResult->hasQuery()) {
                $nested['query'] = $nestedResult->getQuery();
            }
            if ($nestedResult->hasFilter()) {
                $nested['filter'] = $nestedResult->getFilter();
            }
        }

        if (($type === 'bool' || $type === 'constant_score') &&
            $isInFilterContext &&
            isset($nested['filter']['bool'])
        ) {
            $value = $nested['filter']['bool'];
        } else {
            $value = $nested;
        }

        $value = array_merge($this->buildClause(...$args), $value);
        $existing[$boolKey][] = [$type => $value];
    }

    /**
     * @param $field
     * @param $value
     * @param $opts
     */
    protected function buildClause($field = null, $value = null, $opts = [])
    {
        $hasField = $field !== null;
        $hasValue = $value !== null;
        $mainClause = [];

        if ($hasValue) {
            $mainClause = [$field => $value];
        } elseif (is_array($field)) {
            $mainClause = $field;
        } elseif ($hasField) {
            $mainClause = compact('field');
        }
        return array_merge($mainClause, $opts);
    }

    protected function toBool(array $filters)
    {
        $msm = isset($filters['minimum_should_match']) ? $filters['minimum_should_match'] : null;
        $unwrapped = [
            'must' => $this->unwrap($filters['and']),
            'should' => $this->unwrap($filters['or']),
            'must_not' => $this->unwrap($filters['not']),
            'minimum_should_match' => $msm,
        ];

        if (
            count($filters['and']) === 1 &&
            !$unwrapped['should'] &&
            !$unwrapped['must_not']
        ) {
            return $unwrapped['must'];
        }

        $cleaned = [];

        if ($unwrapped['must']) {
            $cleaned['must'] = $unwrapped['must'];
        }
        if ($unwrapped['should']) {
            $cleaned['should'] = $filters['or'];
        }
        if ($unwrapped['must_not']) {
            $cleaned['must_not'] = $filters['not'];
        }
        if ($unwrapped['minimum_should_match'] &&
            count($filters['or']) > 1
        ) {
            $cleaned['minimum_should_match'] = $unwrapped['minimum_should_match'];
        }

        return [
            'bool' => $cleaned
        ];
    }

    protected function sortMerge(&$current, $field, $value)
    {
        if (is_array($value)) {
            $payload = [$field => $value];
        } else {
            $payload = [$field => ['order' => $value]];
        }
        $idx = null;
        foreach ($current as $key => $obj) {
            if (isset($obj[$field])) {
                $idx = $key;
            }
        }
        if ($idx === null) {
            array_push($current, $payload);
        } else {
            $current[$key] = array_merge($current[$key], $value);
        }
    }

    private function unwrap(array $arr)
    {
        return count($arr) > 1 ? $arr : end($arr);
    }
}