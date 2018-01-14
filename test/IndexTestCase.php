<?php

namespace Best\ElasticSearch\BodyBuilder\Test;

use Best\ElasticSearch\BodyBuilder\BodyBuilder;

/**
 * @return BodyBuilder
 */
function bodyBuilder() {
    return new BodyBuilder();
}

class IndexTestCase extends BaseTestCase
{
    public function testBodyBuilderShouldBuildQueryWithNoField()
    {
        $this->plan(1);
        $result = bodyBuilder()->query('match_all');
        $this->assertEquals($result->getQuery(), array("match_all" => array()));
    }
    public function testBodyBuilderShouldBuildQueryWithFieldButNoValue()
    {
        $this->plan(1);
        $result = bodyBuilder()->query('exists', 'user');
        $this->assertEquals($result->getQuery(), array("exists" => array("field" => 'user')));
    }
    public function testBodyBuilderShouldBuildFilterWithoutQuery()
    {
        $this->plan(1);
        $result = bodyBuilder()->filter('term', 'user', 'kimchy')->build();
        $this->assertEquals($result, array("query" => array("bool" => array("filter" => array("term" => array("user" => 'kimchy'))))));
    }
    public function testBodyBuilderShouldBuildV1FilteredQuery()
    {
        $this->plan(1);
        $result = bodyBuilder()->filter('term', 'user', 'kimchy')->build('v1');
        $this->assertEquals($result, array("query" => array("filtered" => array("filter" => array("term" => array("user" => 'kimchy'))))));
    }
    public function testBodyBuilderShouldCreateQueryAndFilter()
    {
        $this->plan(2);
        $result = bodyBuilder()->query('exists', 'user')->filter('term', 'user', 'kimchy');
        $this->assertEquals($result->getQuery(), array("exists" => array("field" => 'user')));
        $this->assertEquals($result->getFilter(), array("term" => array("user" => 'kimchy')));
    }
    public function testBodyBuilderShouldBuildAV1FilteredQuery()
    {
        $this->plan(1);
        $result = bodyBuilder()->query('match', 'message', 'this is a test')->filter('term', 'user', 'kimchy')->build('v1');
        $this->assertEquals($result, array("query" => array("filtered" => array("query" => array("match" => array("message" => 'this is a test')), "filter" => array("term" => array("user" => 'kimchy'))))));
    }
    public function testBodyBuilderShouldBuildAFilteredQuery()
    {
        $this->plan(1);
        $result = bodyBuilder()->query('match', 'message', 'this is a test')->filter('term', 'user', 'kimchy')->build();
        $this->assertEquals($result, array("query" => array("bool" => array("must" => array("match" => array("message" => 'this is a test')), "filter" => array("term" => array("user" => 'kimchy'))))));
    }
    public function testBodyBuilderShouldBuildAFilteredQueryForVersion2X()
    {
        $this->plan(1);
        $result = bodyBuilder()->query('match', 'message', 'this is a test')->filter('term', 'user', 'kimchy')->build('v2');
        $this->assertEquals($result, array("query" => array("bool" => array("must" => array("match" => array("message" => 'this is a test')), "filter" => array("term" => array("user" => 'kimchy'))))));
    }
    public function testBodyBuilderShouldSortWithDefaultSortDirection()
    {
        $this->plan(1);
        $result = bodyBuilder()->sort('timestamp')->build();
        $this->assertEquals($result, array("sort" => array(array("timestamp" => array("order" => 'asc')))));
    }
    public function testBodyBuilderShouldSetFromOnBody()
    {
        $this->plan(1);
        $result = bodyBuilder()->from(10)->build();
        $this->assertEquals($result, array("from" => 10));
    }
    public function testBodyBuilderShouldSetSizeOnBody()
    {
        $this->plan(1);
        $result = bodyBuilder()->size(10)->build();
        $this->assertEquals($result, array("size" => 10));
    }
    public function testBodyBuilderShouldSetAnyKeyValueOnBody()
    {
        $this->plan(1);
        $result = bodyBuilder()->rawOption('a', array("b" => 'c'))->build();
        $this->assertEquals($result, array("a" => array("b" => 'c')));
    }
    public function testBodyBuilderShouldBuildQueryWithFieldAndValue()
    {
        $this->plan(1);
        $result = bodyBuilder()->query('term', 'user', 'kimchy');
        $this->assertEquals($result->getQuery(), array("term" => array("user" => 'kimchy')));
    }
    public function testBodyBuilderShouldBuildQueryWithFieldAndObjectValue()
    {
        $this->plan(1);
        $result = bodyBuilder()->query('range', 'date', array("gt" => 'now-1d'));
        $this->assertEquals($result->getQuery(), array("range" => array("date" => array("gt" => 'now-1d'))));
    }
    public function testBodyBuilderShouldBuildQueryWithMoreOptions()
    {
        $this->plan(1);
        $result = bodyBuilder()->query('geo_distance', 'point', array("lat" => 40, "lon" => 20), array("distance" => '12km'));
        $this->assertEquals($result->getQuery(), array("geo_distance" => array("distance" => '12km', "point" => array("lat" => 40, "lon" => 20))));
    }
    public function testBodyBuilderShouldBuildNestedQueries()
    {
        $this->plan(1);
        $result = bodyBuilder()->query('nested', 'path', 'obj1', function ($q) {
            $q->query('match', 'obj1.color', 'blue');
        });
        $this->assertEquals($result->getQuery(), array("nested" => array("path" => 'obj1', "query" => array("match" => array("obj1.color" => 'blue')))));
    }
    public function testBodyBuilderShouldNestBoolMergedQueries()
    {
        $this->plan(1);
        $result = bodyBuilder()->query('nested', 'path', 'obj1', array("score_mode" => 'avg'), function ($q) {
            return $q->query('match', 'obj1.name', 'blue')->query('range', 'obj1.count', array("gt" => 5));
        });
        $this->assertEquals($result->getQuery(), array("nested" => array("path" => 'obj1', "score_mode" => 'avg', "query" => array("bool" => array("must" => array(array("match" => array("obj1.name" => 'blue')), array("range" => array("obj1.count" => array("gt" => 5)))))))));
    }
    public function testBodyBuilderShouldMakeThisChainedNestedQuery()
    {
        $this->plan(1);
        $result = bodyBuilder()->query('match', 'title', 'eggs')->query('nested', 'path', 'comments', array("score_mode" => 'max'), function ($q) {
            return $q->query('match', 'comments.name', 'john')->query('match', 'comments.age', 28);
        });
        $this->assertEquals($result->getQuery(), array("bool" => array("must" => array(array("match" => array("title" => 'eggs')), array("nested" => array("path" => 'comments', "score_mode" => 'max', "query" => array("bool" => array("must" => array(array("match" => array("comments.name" => 'john')), array("match" => array("comments.age" => 28)))))))))));
    }
    public function testBodyBuilderShouldCreateThisBigAssQuery()
    {
        $this->plan(1);
        $result = bodyBuilder()->query('constant_score', function ($q) {
            return $q->orFilter('term', 'created_by.user_id', 'abc')->orFilter('nested', 'path', 'doc_meta', function ($q) {
                return $q->query('constant_score', function ($q) {
                    return $q->filter('term', 'doc_meta.user_id', 'abc');
                });
            })->orFilter('nested', 'path', 'tests', function ($q) {
                return $q->query('constant_score', function ($q) {
                    return $q->filter('term', 'tests.created_by.user_id', 'abc');
                });
            });
        });
        $this->assertEquals($result->getQuery(), array("constant_score" => array("filter" => array("bool" => array("should" => array(array("term" => array("created_by.user_id" => 'abc')), array("nested" => array("path" => 'doc_meta', "query" => array("constant_score" => array("filter" => array("term" => array("doc_meta.user_id" => 'abc')))))), array("nested" => array("path" => 'tests', "query" => array("constant_score" => array("filter" => array("term" => array("tests.created_by.user_id" => 'abc'))))))))))));
    }
    public function testBodyBuilderShouldCombineQueriesFiltersAggregations()
    {
        $this->plan(1);
        $result = bodyBuilder()->query('match', 'message', 'this is a test')->filter('term', 'user', 'kimchy')->filter('term', 'user', 'herald')->orFilter('term', 'user', 'johnny')->notFilter('term', 'user', 'cassie')->aggregation('terms', 'user')->build();
        $this->assertEquals($result, array("query" => array("bool" => array("must" => array("match" => array("message" => 'this is a test')), "filter" => array("bool" => array("must" => array(array("term" => array("user" => 'kimchy')), array("term" => array("user" => 'herald'))), "should" => array(array("term" => array("user" => 'johnny'))), "must_not" => array(array("term" => array("user" => 'cassie'))))))), "aggs" => array("agg_terms_user" => array("terms" => array("field" => 'user')))));
    }
    public function testBodybuilderShouldAllowRebuilding()
    {
        $this->plan(2);
        $body = bodyBuilder()->filter('match', 'message', 'this is a test');
        $this->assertEquals($body->build('v1'), array("query" => array("filtered" => array("filter" => array("match" => array("message" => 'this is a test'))))));
        $this->assertEquals($body->build(), array("query" => array("bool" => array("filter" => array("match" => array("message" => 'this is a test'))))));
    }
    public function testBodybuilderShouldAddANotFilter()
    {
        $this->plan(1);
        $result = bodyBuilder()->notFilter('match', 'message', 'this is a test')->build();
        $this->assertEquals($result, array("query" => array("bool" => array("filter" => array("bool" => array("must_not" => array(array("match" => array("message" => 'this is a test')))))))));
    }
    public function testBodybuilderOrFilter()
    {
        $this->plan(1);
        $result = bodyBuilder()->filter('or', array(array("term" => array("user" => 'kimchy')), array("term" => array("user" => 'tony'))))->build();
        $this->assertEquals($result, array("query" => array("bool" => array("filter" => array("or" => array(array("term" => array("user" => 'kimchy')), array("term" => array("user" => 'tony'))))))));
    }
    public function testBodybuilderDynamicFilter()
    {
        $this->plan(1);
        $result = bodyBuilder()->filter('constant_score', function ($f) {
            $f->filter('term', 'user', 'kimchy');
        })->filter('term', 'message', 'this is a test')->build();
        $this->assertEquals($result, array("query" => array("bool" => array("filter" => array("bool" => array("must" => array(array("constant_score" => array("filter" => array("term" => array("user" => 'kimchy')))), array("term" => array("message" => 'this is a test')))))))));
    }
    public function testBodybuilderComplexDynamicFilter()
    {
        $this->plan(3);
        $result = bodyBuilder()->orFilter('bool', function ($f) {
            $f->filter('terms', 'tags', array('Popular'));
            $f->filter('terms', 'brands', array('A', 'B'));
            return $f;
        })->orFilter('bool', function ($f) {
            $f->filter('terms', 'tags', array('Emerging'));
            $f->filter('terms', 'brands', array('C'));
            return $f;
        })->orFilter('bool', function ($f) {
            $f->filter('terms', 'tags', array('Rumor'));
            $f->filter('terms', 'companies', array('A', 'C', 'D'));
            return $f;
        })->build();
        $this->assertEquals($result, array("query" => array("bool" => array("filter" => array("bool" => array("should" => array(array("bool" => array("must" => array(array("terms" => array("tags" => array('Popular'))), array("terms" => array("brands" => array('A', 'B')))))), array("bool" => array("must" => array(array("terms" => array("tags" => array('Emerging'))), array("terms" => array("brands" => array('C')))))), array("bool" => array("must" => array(array("terms" => array("tags" => array('Rumor'))), array("terms" => array("companies" => array('A', 'C', 'D')))))))))))));
        $this->assertEquals($result->query->bool->filter->bool->should, array(array("bool" => array("must" => array(array("terms" => array("tags" => array('Popular'))), array("terms" => array("brands" => array('A', 'B')))))), array("bool" => array("must" => array(array("terms" => array("tags" => array('Emerging'))), array("terms" => array("brands" => array('C')))))), array("bool" => array("must" => array(array("terms" => array("tags" => array('Rumor'))), array("terms" => array("companies" => array('A', 'C', 'D'))))))));
        $this->assertEquals($result->query->bool->filter->bool->should[0], array("bool" => array("must" => array(array("terms" => array("tags" => array('Popular'))), array("terms" => array("brands" => array('A', 'B')))))));
    }
    public function testBodybuilderMinimumShouldMatchFilter()
    {
        $this->plan(1);
        $result = bodyBuilder()->orFilter('term', 'user', 'kimchy')->orFilter('term', 'user', 'tony')->filterMinimumShouldMatch(2)->build();
        $this->assertEquals($result, array("query" => array("bool" => array("filter" => array("bool" => array("should" => array(array("term" => array("user" => 'kimchy')), array("term" => array("user" => 'tony'))), "minimum_should_match" => 2))))));
    }
    public function testBodybuilderMinimumShouldMatchQuery()
    {
        $this->plan(1);
        $result = bodyBuilder()->orQuery('term', 'user', 'kimchy')->orQuery('term', 'user', 'tony')->queryMinimumShouldMatch(2)->build();
        $this->assertEquals($result, array("query" => array("bool" => array("should" => array(array("term" => array("user" => 'kimchy')), array("term" => array("user" => 'tony'))), "minimum_should_match" => 2))));
    }
    public function testBodybuilderMinimumShouldMatchQueryAndFilter()
    {
        $this->plan(1);
        $result = bodyBuilder()->orQuery('term', 'user', 'kimchy')->orQuery('term', 'user', 'tony')->orFilter('term', 'user', 'kimchy')->orFilter('term', 'user', 'tony')->filterMinimumShouldMatch(1)->queryMinimumShouldMatch(2)->build();
        $this->assertEquals($result, array("query" => array("bool" => array("should" => array(array("term" => array("user" => 'kimchy')), array("term" => array("user" => 'tony'))), "minimum_should_match" => 2, "filter" => array("bool" => array("should" => array(array("term" => array("user" => 'kimchy')), array("term" => array("user" => 'tony'))), "minimum_should_match" => 1))))));
    }
}