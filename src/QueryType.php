<?php

namespace Best\ElasticSearch\BodyBuilder;

/**
 * Top-level query types.
 */
class QueryType
{
    const TERM = 'term';
    const IDS = 'ids';
    const TERMS = 'terms';
    const EXISTS = 'exists';
    const MISSING = 'missing';
    const CONSTANT_SCORE = 'constant_score';
    const QUERY_STRING = 'query_string';
    const MATCH = 'match';
    const MATCH_NONE = 'match_none';
    const MATCH_ALL = 'match_all';
    const MATCH_PHRASE = 'match_phrase';
    const SIMPLE_QUERY_STRING = 'simple_query_string';
    const COMMON = 'common';
    const RANGE = 'range';
    const NESTED = 'nested';
    const HAS_PARENT = 'has_parent';
    const HAS_CHILD = 'has_child';
    const GEO_BOUNDING_BOX = 'geo_bounding_box';
    const GEO_DISTANCE = 'geo_distance';
    const GEO_DISTANCE_RANGE = 'geo_distance_range';
    const GEO_POLYGON = 'geo_polygon';
    const TYPE = 'type';
    const PREFIX = 'prefix';
    const WILDCARD = 'wildcard';
    const REGEXP = 'regexp';
    const FUZZY = 'fuzzy';
    const GEOHASH_CELL = 'geohash_cell';
    const MORE_LIKE_THIS = 'more_like_this';
    const TEMPLATE = 'template';
    const SCRIPT = 'script';
    const OR_QUERY = 'or';  // 'or' is a reserved word in PHP 5.6
}