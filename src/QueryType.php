<?php

namespace Best\ElasticSearch\BodyBuilder;

/**
 * Top-level query types.
 */
final class QueryType
{
    const Term = 'term';
    const Ids = 'ids';
    const Terms = 'terms';
    const Exists = 'exists';
    const Missing = 'missing';
    const ConstantScore = 'constant_score';
    const QueryString = 'query_string';
    const Match = 'match';
    const MatchNone = 'match_none';
    const MatchAll = 'match_all';
    const MatchPhrase = 'match_phrase';
    const SimpleQueryString = 'simple_query_string';
    const Common = 'common';
    const Range = 'range';
    const Nested = 'nested';
    const HasParent = 'has_parent';
    const HasChild = 'has_child';
    const GeoBoundingBox = 'geo_bounding_box';
    const GeoDistance = 'geo_distance';
    const GeoDistanceRange = 'geo_distance_range';
    const GeoPolygon = 'geo_polygon';
    const Type = 'type';
    const Prefix = 'prefix';
    const Wildcard = 'wildcard';
    const Regexp = 'regexp';
    const Fuzzy = 'fuzzy';
    const GeohashCell = 'geohash_cell';
    const MoreLikeThis = 'more_like_this';
    const Template = 'template';
    const Script = 'script';
    const Or_ = 'or';
}