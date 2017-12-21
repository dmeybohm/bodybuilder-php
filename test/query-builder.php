<?php

class QueryBuilderTest extends \PHPUnit_Framework_TestCase
{
    public function testQueryBuilderMatchAll()
    {
        $t->plan(1);
        $result = queryBuilder()->query('match_all');
        $t->deepEqual($result->getQuery(), array("match_all" => array()));
    }
    public function testQueryBuilderMatchAllWithBoost()
    {
        $t->plan(1);
        $result = queryBuilder()->query('match_all', array("boost" => 1.2));
        $t->deepEqual($result->getQuery(), array("match_all" => array("boost" => 1.2)));
    }
    public function testQueryBuilderMatchNone()
    {
        $t->plan(1);
        $result = queryBuilder()->query('match_none');
        $t->deepEqual($result->getQuery(), array("match_none" => array()));
    }
    public function testQueryBuilderMatch()
    {
        $t->plan(1);
        $result = queryBuilder()->query('match', 'message', 'this is a test');
        $t->deepEqual($result->getQuery(), array("match" => array("message" => 'this is a test')));
    }
    public function testQueryBuilderMatchEmptyString()
    {
        $t->plan(1);
        $result = queryBuilder()->query('match', 'message', '');
        $t->deepEqual($result->getQuery(), array("match" => array("message" => '')));
    }
    public function testQueryBuilderMatchWithOptions()
    {
        $t->plan(1);
        $result = queryBuilder()->query('match', 'message', array("query" => 'this is a test', "operator" => 'and'));
        $t->deepEqual($result->getQuery(), array("match" => array("message" => array("query" => 'this is a test', "operator" => 'and'))));
    }
    public function testQueryBuilderMatchPhrase()
    {
        $t->plan(1);
        $result = queryBuilder()->query('match_phrase', 'message', 'this is a test');
        $t->deepEqual($result->getQuery(), array("match_phrase" => array("message" => 'this is a test')));
    }
    public function testQueryBuilderMatchPhraseWithOptions()
    {
        $t->plan(1);
        $result = queryBuilder()->query('match_phrase', 'message', array("query" => 'this is a test', "analyzer" => 'my_analyzer'));
        $t->deepEqual($result->getQuery(), array("match_phrase" => array("message" => array("query" => 'this is a test', "analyzer" => 'my_analyzer'))));
    }
    public function testQueryBuilderCommon()
    {
        $t->plan(1);
        $result = queryBuilder()->query('common', 'body', array("query" => 'this is bonsai cool', "cutoff_frequency" => 0.001));
        $t->deepEqual($result->getQuery(), array("common" => array("body" => array("query" => 'this is bonsai cool', "cutoff_frequency" => 0.001))));
    }
    public function testQueryBuilderCommon()
    {
        $t->plan(1);
        $result = queryBuilder()->query('common', 'body', array("query" => 'this is bonsai cool', "cutoff_frequency" => 0.001));
        $t->deepEqual($result->getQuery(), array("common" => array("body" => array("query" => 'this is bonsai cool', "cutoff_frequency" => 0.001))));
    }
    public function testQueryBuilderQueryString()
    {
        $t->plan(1);
        $result = queryBuilder()->query('query_string', 'query', 'this AND that OR thus');
        $t->deepEqual($result->getQuery(), array("query_string" => array("query" => 'this AND that OR thus')));
    }
    public function testQueryBuilderQueryStringWithOptions()
    {
        $t->plan(1);
        $result = queryBuilder()->query('query_string', 'query', 'this AND that OR thus', array("fields" => array('content', 'name')));
        $t->deepEqual($result->getQuery(), array("query_string" => array("query" => 'this AND that OR thus', "fields" => array('content', 'name'))));
    }
    public function testQueryBuilderQueryStringAlternative()
    {
        $t->plan(1);
        $result = queryBuilder()->query('query_string', array("query" => 'this AND that OR thus', "fields" => array('content', 'name')));
        $t->deepEqual($result->getQuery(), array("query_string" => array("query" => 'this AND that OR thus', "fields" => array('content', 'name'))));
    }
    public function testQueryBuilderSimpleQueryString()
    {
        $t->plan(1);
        $result = queryBuilder()->query('simple_query_string', 'query', 'foo bar baz');
        $t->deepEqual($result->getQuery(), array("simple_query_string" => array("query" => 'foo bar baz')));
    }
    public function testQueryBuilderTerm()
    {
        $t->plan(1);
        $result = queryBuilder()->query('term', 'user', 'kimchy');
        $t->deepEqual($result->getQuery(), array("term" => array("user" => 'kimchy')));
    }
    public function testQueryBuilderTermWithBoost()
    {
        $t->plan(1);
        $result = queryBuilder()->query('term', 'status', array("value" => 'urgent', "boost" => '2.0'));
        $t->deepEqual($result->getQuery(), array("term" => array("status" => array("value" => 'urgent', "boost" => '2.0'))));
    }
    public function testQueryBuilderTermMultiple()
    {
        $t->plan(1);
        $result = queryBuilder()->orQuery('term', 'status', array("value" => 'urgent', "boost" => '2.0'))->orQuery('term', 'status', 'normal');
        $t->deepEqual($result->getQuery(), array("bool" => array("should" => array(array("term" => array("status" => array("value" => 'urgent', "boost" => '2.0'))), array("term" => array("status" => 'normal'))))));
    }
    public function testQueryBuilderTerms()
    {
        $t->plan(1);
        $result = queryBuilder()->query('terms', 'user', array('kimchy', 'elastic'));
        $t->deepEqual($result->getQuery(), array("terms" => array("user" => array('kimchy', 'elastic'))));
    }
    public function testQueryBuilderRange()
    {
        $t->plan(1);
        $result = queryBuilder()->query('range', 'age', array("gte" => 10));
        $t->deepEqual($result->getQuery(), array("range" => array("age" => array("gte" => 10))));
    }
    public function testQueryBuilderExists()
    {
        $t->plan(1);
        $result = queryBuilder()->query('exists', 'user');
        $t->deepEqual($result->getQuery(), array("exists" => array("field" => 'user')));
    }
    public function testQueryBuilderMissing()
    {
        $t->plan(1);
        $result = queryBuilder()->query('missing', 'user');
        $t->deepEqual($result->getQuery(), array("missing" => array("field" => 'user')));
    }
    public function testQueryBuilderPrefix()
    {
        $t->plan(1);
        $result = queryBuilder()->query('prefix', 'user', 'ki');
        $t->deepEqual($result->getQuery(), array("prefix" => array("user" => 'ki')));
    }
    public function testQueryBuilderPrefixWithBoost()
    {
        $t->plan(1);
        $result = queryBuilder()->query('prefix', 'user', array("value" => 'ki', "boost" => 2));
        $t->deepEqual($result->getQuery(), array("prefix" => array("user" => array("value" => 'ki', "boost" => 2))));
    }
    public function testQueryBuilderWildcard()
    {
        $t->plan(1);
        $result = queryBuilder()->query('wildcard', 'user', 'ki*y');
        $t->deepEqual($result->getQuery(), array("wildcard" => array("user" => 'ki*y')));
    }
    public function testQueryBuilderRegexp()
    {
        $t->plan(1);
        $result = queryBuilder()->query('regexp', 'name.first', 's.*y');
        $t->deepEqual($result->getQuery(), array("regexp" => array("name.first" => 's.*y')));
    }
    public function testQueryBuilderFuzzy()
    {
        $t->plan(1);
        $result = queryBuilder()->query('fuzzy', 'user', 'ki');
        $t->deepEqual($result->getQuery(), array("fuzzy" => array("user" => 'ki')));
    }
    public function testQueryBuilderType()
    {
        $t->plan(1);
        $result = queryBuilder()->query('type', 'value', 'my_type');
        $t->deepEqual($result->getQuery(), array("type" => array("value" => 'my_type')));
    }
    public function testQueryBuilderIds()
    {
        $t->plan(1);
        $result = queryBuilder()->query('ids', 'type', 'my_ids', array("values" => array('1', '4', '100')));
        $t->deepEqual($result->getQuery(), array("ids" => array("type" => 'my_ids', "values" => array('1', '4', '100'))));
    }
    public function testQueryBuilderConstantScore()
    {
        $t->plan(1);
        $result = queryBuilder()->query('constant_score', array("boost" => 1.2), function ($q) {
            return $q->filter('term', 'user', 'kimchy');
        });
        $t->deepEqual($result->getQuery(), array("constant_score" => array("filter" => array("term" => array("user" => 'kimchy')), "boost" => 1.2)));
    }
    public function testQueryBuilderNested()
    {
        $t->plan(1);
        $result = queryBuilder()->query('nested', array("path" => 'obj1', "score_mode" => 'avg'), function ($q) {
            return $q->query('match', 'obj1.name', 'blue')->query('range', 'obj1.count', array("gt" => 5));
        });
        $t->deepEqual($result->getQuery(), array("nested" => array("path" => 'obj1', "score_mode" => 'avg', "query" => array("bool" => array("must" => array(array("match" => array("obj1.name" => 'blue')), array("range" => array("obj1.count" => array("gt" => 5)))))))));
    }
    public function testQueryBuilderHasChild()
    {
        $t->plan(1);
        $result = queryBuilder()->query('has_child', 'type', 'blog_tag', function ($q) {
            return $q->query('term', 'tag', 'something');
        });
        $t->deepEqual($result->getQuery(), array("has_child" => array("type" => 'blog_tag', "query" => array("term" => array("tag" => 'something')))));
    }
    public function testQueryBuilderHasParent()
    {
        $t->plan(1);
        $result = queryBuilder()->query('has_parent', 'parent_tag', 'blog', function ($q) {
            return $q->query('term', 'tag', 'something');
        });
        $t->deepEqual($result->getQuery(), array("has_parent" => array("parent_tag" => 'blog', "query" => array("term" => array("tag" => 'something')))));
    }
    public function testQueryBuilderGeoBoundingBox()
    {
        $t->plan(1);
        $result = queryBuilder()->query('geo_bounding_box', 'pin.location', array("top_left" => array("lat" => 40, "lon" => -74), "bottom_right" => array("lat" => 40, "lon" => -74)), array("relation" => 'within'));
        $t->deepEqual($result->getQuery(), array("geo_bounding_box" => array("relation" => 'within', "pin.location" => array("top_left" => array("lat" => 40, "lon" => -74), "bottom_right" => array("lat" => 40, "lon" => -74)))));
    }
    public function testQueryBuilderGeoDistance()
    {
        $t->plan(1);
        $result = queryBuilder()->query('geo_distance', 'pin.location', array("lat" => 40, "lon" => -74), array("distance" => '200km'));
        $t->deepEqual($result->getQuery(), array("geo_distance" => array("distance" => '200km', "pin.location" => array("lat" => 40, "lon" => -74))));
    }
    public function testQueryBuilderGeoDistanceRange()
    {
        $t->plan(1);
        $result = queryBuilder()->query('geo_distance_range', 'pin.location', array("lat" => 40, "lon" => -74), array("from" => '100km', "to" => '200km'));
        $t->deepEqual($result->getQuery(), array("geo_distance_range" => array("from" => '100km', "to" => '200km', "pin.location" => array("lat" => 40, "lon" => -74))));
    }
    public function testQueryBuilderGeoPolygon()
    {
        $t->plan(1);
        $result = queryBuilder()->query('geo_polygon', 'person.location', array("points" => array(array("lat" => 40, "lon" => -70), array("lat" => 30, "lon" => -80), array("lat" => 20, "lon" => -90))));
        $t->deepEqual($result->getQuery(), array("geo_polygon" => array("person.location" => array("points" => array(array("lat" => 40, "lon" => -70), array("lat" => 30, "lon" => -80), array("lat" => 20, "lon" => -90))))));
    }
    public function testQueryBuilderGeohashCell()
    {
        $t->plan(1);
        $result = queryBuilder()->query('geohash_cell', 'pin', array("lat" => 13.408, "lon" => 52.5186), array("precision" => 3, "neighbors" => true));
        $t->deepEqual($result->getQuery(), array("geohash_cell" => array("pin" => array("lat" => 13.408, "lon" => 52.5186), "precision" => 3, "neighbors" => true)));
    }
    public function testQueryBuilderMoreLikeThis()
    {
        $t->plan(1);
        $result = queryBuilder()->query('more_like_this', array("fields" => array('title', 'description'), "like" => "Once upon a time", "min_term_freq" => 1, "max_query_terms" => 12));
        $t->deepEqual($result->getQuery(), array("more_like_this" => array("fields" => array('title', 'description'), "like" => "Once upon a time", "min_term_freq" => 1, "max_query_terms" => 12)));
    }
    public function testQueryBuilderTemplate()
    {
        $t->plan(1);
        $result = queryBuilder()->query('template', array("inline" => array("match" => array("text" => '{{query_string}}')), "params" => array("query_string" => 'all about search')));
        $t->deepEqual($result->getQuery(), array("template" => array("inline" => array("match" => array("text" => '{{query_string}}')), "params" => array("query_string" => 'all about search'))));
    }
    public function testQueryBuilderScript()
    {
        $t->plan(1);
        $result = queryBuilder()->query('script', 'script', array("inline" => "doc['num1'].value > 1", "lang" => 'painless'));
        $t->deepEqual($result->getQuery(), array("script" => array("script" => array("inline" => "doc['num1'].value > 1", "lang" => 'painless'))));
    }
    public function testQueryBuilderOr()
    {
        $t->plan(1);
        $result = queryBuilder()->query('or', array(array("term" => array("user" => 'kimchy')), array("term" => array("user" => 'tony'))));
        $t->deepEqual($result->getQuery(), array("or" => array(array("term" => array("user" => 'kimchy')), array("term" => array("user" => 'tony')))));
    }
    public function testQueryBuilderMinimumShouldMatchWithOneQueryIgnoresMinimum()
    {
        $t->plan(1);
        $result = queryBuilder()->orQuery('term', 'status', 'alert')->queryMinimumShouldMatch(2);
        $t->deepEqual($result->getQuery(), array("bool" => array("should" => array(array("term" => array("status" => 'alert'))))));
    }
    public function testQueryBuilderMinimumShouldMatchWithMultipleCombination()
    {
        $t->plan(1);
        $result = queryBuilder()->orQuery('term', 'status', 'alert')->orQuery('term', 'status', 'normal')->queryMinimumShouldMatch('2<-25% 9<-3');
        $t->deepEqual($result->getQuery(), array("bool" => array("should" => array(array("term" => array("status" => 'alert')), array("term" => array("status" => 'normal'))), "minimum_should_match" => '2<-25% 9<-3')));
    }
    public function testQueryBuilderMinimumShouldMatchWithMultipleQueries()
    {
        $t->plan(1);
        $result = queryBuilder()->orQuery('term', 'status', 'alert')->orQuery('term', 'status', 'normal')->queryMinimumShouldMatch(2);
        $t->deepEqual($result->getQuery(), array("bool" => array("should" => array(array("term" => array("status" => 'alert')), array("term" => array("status" => 'normal'))), "minimum_should_match" => 2)));
    }
}