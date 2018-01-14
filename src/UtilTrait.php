<?php

namespace Best\ElasticSearch\BodyBuilder;

trait UtilTrait
{
    /**
     *
     * @var boolean
     */
    protected $isInFilterContext = false;

    /**
     * Options
     *
     * @var array
     */
    protected $options = [];

    /**
     * @param &$existing
     * @param $boolKey
     * @param $type
     * @param array ...$args
     * @return array
     */
    protected function pushQuery(&$existing, $boolKey, $type, ...$args)
    {
        $nested = [];
        if (is_callable(end($args))) {
            throw new \RuntimeException("Unimplemened");
        }

        if (($type === 'bool' || $type === 'constant_score') &&
            $this->isInFilterContext &&
            isset($nested['filter']['bool'])
        ) {
            $value = array_merge($this->buildClause(...$args), $nested['filter']['bool']);
            $existing[$boolKey][] = [$type => $value];
        } else {
            $value = array_merge($this->buildClause(...$args), $nested);
            $existing[$boolKey][] = [$type => $value];
        }
        return $existing;
    }

    /**
     * @param $field
     * @param $value
     * @param $opts
     */
    protected function buildClause($field = null, $value = null, $opts = [])
    {
        // @todo see if !empty is right here:
        $hasField = !empty($field);
        $hasValue = !empty($value);
        $mainClause = [];

        if ($hasValue) {
            $mainClause = [$field => $value];
        } elseif (is_array($field)) {
            $mainClause = $field;
        } elseif ($hasField) {
            $mainClause = [$field];
        }
        return array_merge($mainClause, $opts);
    }

    protected function toBool($filters)
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
        if (
            $unwrapped['minimum_should_match'] &&
            count($filters['or']) > 1
        ) {
            $cleaned['minimum_should_match'] = $unwrapped['minimum_should_match'];
        }

        return [
            'bool' => $cleaned
        ];
    }

    private function unwrap(array $arr)
    {
        return count($arr) > 1 ? $arr : end($arr);
    }
}