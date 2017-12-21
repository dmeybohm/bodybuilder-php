<?php

class IndexTest extends \PHPUnit_Framework_TestCase
{
    public function testBodyBuilderShouldBuildQueryWithNoField()
    {
        $t->plan(1);
        $result = bodyBuilder()->query('match_all');
        $t->deepEqual($result->getQuery(), array("match_all" => array()));
    }
    public function testBodyBuilderShouldBuildQueryWithFieldButNoValue()
    {
        $t->plan(1);
        $result = bodyBuilder()->query('exists', 'user');
        $t->deepEqual($result->getQuery(), array("exists" => array("field" => 'user')));
    }
    public function testBodyBuilderShouldBuildFilterWithoutQuery()
    {
        $t->plan(1);
        $result = bodyBuilder()->filter('term', 'user', 'kimchy')->build();
        $t->deepEqual($result, array("query" => array("bool" => array("filter" => array("term" => array("user" => 'kimchy'))))));
    }
    public function testBodyBuilderShouldBuildV1FilteredQuery()
    {
        $t->plan(1);
        $result = bodyBuilder()->filter('term', 'user', 'kimchy')->build('v1');
        $t->deepEqual($result, array("query" => array("filtered" => array("filter" => array("term" => array("user" => 'kimchy'))))));
    }
    public function testBodyBuilderShouldCreateQueryAndFilter()
    {
        $t->plan(2);
        $result = bodyBuilder()->query('exists', 'user')->filter('term', 'user', 'kimchy');
        $t->deepEqual($result->getQuery(), array("exists" => array("field" => 'user')));
        $t->deepEqual($result->getFilter(), array("term" => array("user" => 'kimchy')));
    }
    public function testBodyBuilderShouldBuildAV1FilteredQuery()
    {
        $t->plan(1);
        $result = bodyBuilder()->query('match', 'message', 'this is a test')->filter('term', 'user', 'kimchy')->build('v1');
        $t->deepEqual($result, array("query" => array("filtered" => array("query" => array("match" => array("message" => 'this is a test')), "filter" => array("term" => array("user" => 'kimchy'))))));
    }
    public function testBodyBuilderShouldBuildAFilteredQuery()
    {
        $t->plan(1);
        $result = bodyBuilder()->query('match', 'message', 'this is a test')->filter('term', 'user', 'kimchy')->build();
        $t->deepEqual($result, array("query" => array("bool" => array("must" => array("match" => array("message" => 'this is a test')), "filter" => array("term" => array("user" => 'kimchy'))))));
    }
    public function testBodyBuilderShouldBuildAFilteredQueryForVersion2X()
    {
        $t->plan(1);
        $result = bodyBuilder()->query('match', 'message', 'this is a test')->filter('term', 'user', 'kimchy')->build('v2');
        $t->deepEqual($result, array("query" => array("bool" => array("must" => array("match" => array("message" => 'this is a test')), "filter" => array("term" => array("user" => 'kimchy'))))));
    }
    public function testBodyBuilderShouldSortWithDefaultSortDirection()
    {
        $t->plan(1);
        $result = bodyBuilder()->sort('timestamp')->build();
        $t->deepEqual($result, array("sort" => array(array("timestamp" => array("order" => 'asc')))));
    }
    public function testBodyBuilderShouldSetFromOnBody()
    {
        $t->plan(1);
        $result = bodyBuilder()->from(10)->build();
        $t->deepEqual($result, array("from" => 10));
    }
    public function testBodyBuilderShouldSetSizeOnBody()
    {
        $t->plan(1);
        $result = bodyBuilder()->size(10)->build();
        $t->deepEqual($result, array("size" => 10));
    }
    public function testBodyBuilderShouldSetAnyKeyValueOnBody()
    {
        $t->plan(1);
        $result = bodyBuilder()->rawOption('a', array("b" => 'c'))->build();
        $t->deepEqual($result, array("a" => array("b" => 'c')));
    }
    public function testBodyBuilderShouldBuildQueryWithFieldAndValue()
    {
        $t->plan(1);
        $result = bodyBuilder()->query('term', 'user', 'kimchy');
        $t->deepEqual($result->getQuery(), array("term" => array("user" => 'kimchy')));
    }
    public function testBodyBuilderShouldBuildQueryWithFieldAndObjectValue()
    {
        $t->plan(1);
        $result = bodyBuilder()->query('range', 'date', array("gt" => 'now-1d'));
        $t->deepEqual($result->getQuery(), array("range" => array("date" => array("gt" => 'now-1d'))));
    }
    public function testBodyBuilderShouldBuildQueryWithMoreOptions()
    {
        $t->plan(1);
        $result = bodyBuilder()->query('geo_distance', 'point', array("lat" => 40, "lon" => 20), array("distance" => '12km'));
        $t->deepEqual($result->getQuery(), array("geo_distance" => array("distance" => '12km', "point" => array("lat" => 40, "lon" => 20))));
    }
    public function testBodyBuilderShouldBuildNestedQueries()
    {
        $t->plan(1);
        $result = bodyBuilder()->query('nested', 'path', 'obj1', function ($q) {
            $q->query('match', 'obj1.color', 'blue');
        });
        $t->deepEqual($result->getQuery(), array("nested" => array("path" => 'obj1', "query" => array("match" => array("obj1.color" => 'blue')))));
    }
    public function testBodyBuilderShouldNestBoolMergedQueries()
    {
        $t->plan(1);
        $result = bodyBuilder()->query('nested', 'path', 'obj1', array("score_mode" => 'avg'), function ($q) {
            return $q->query('match', 'obj1.name', 'blue')->query('range', 'obj1.count', array("gt" => 5));
        });
        $t->deepEqual($result->getQuery(), array("nested" => array("path" => 'obj1', "score_mode" => 'avg', "query" => array("bool" => array("must" => array(array("match" => array("obj1.name" => 'blue')), array("range" => array("obj1.count" => array("gt" => 5)))))))));
    }
    public function testBodyBuilderShouldMakeThisChainedNestedQuery()
    {
        $t->plan(1);
        $result = bodyBuilder()->query('match', 'title', 'eggs')->query('nested', 'path', 'comments', array("score_mode" => 'max'), function ($q) {
            return $q->query('match', 'comments.name', 'john')->query('match', 'comments.age', 28);
        });
        $t->deepEqual($result->getQuery(), array("bool" => array("must" => array(array("match" => array("title" => 'eggs')), array("nested" => array("path" => 'comments', "score_mode" => 'max', "query" => array("bool" => array("must" => array(array("match" => array("comments.name" => 'john')), array("match" => array("comments.age" => 28)))))))))));
    }
    public function testBodyBuilderShouldCreateThisBigAssQuery()
    {
        $t->plan(1);
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
        $t->deepEqual($result->getQuery(), array("constant_score" => array("filter" => array("bool" => array("should" => array(array("term" => array("created_by.user_id" => 'abc')), array("nested" => array("path" => 'doc_meta', "query" => array("constant_score" => array("filter" => array("term" => array("doc_meta.user_id" => 'abc')))))), array("nested" => array("path" => 'tests', "query" => array("constant_score" => array("filter" => array("term" => array("tests.created_by.user_id" => 'abc'))))))))))));
    }
    public function testBodyBuilderShouldCombineQueriesFiltersAggregations()
    {
        $t->plan(1);
        $result = bodyBuilder()->query('match', 'message', 'this is a test')->filter('term', 'user', 'kimchy')->filter('term', 'user', 'herald')->orFilter('term', 'user', 'johnny')->notFilter('term', 'user', 'cassie')->aggregation('terms', 'user')->build();
        $t->deepEqual($result, array("query" => array("bool" => array("must" => array("match" => array("message" => 'this is a test')), "filter" => array("bool" => array("must" => array(array("term" => array("user" => 'kimchy')), array("term" => array("user" => 'herald'))), "should" => array(array("term" => array("user" => 'johnny'))), "must_not" => array(array("term" => array("user" => 'cassie'))))))), "aggs" => array("agg_terms_user" => array("terms" => array("field" => 'user')))));
    }
    public function testBodybuilderShouldAllowRebuilding()
    {
        $t->plan(2);
        $body = bodyBuilder()->filter('match', 'message', 'this is a test');
        $t->deepEqual($body->build('v1'), array("query" => array("filtered" => array("filter" => array("match" => array("message" => 'this is a test'))))));
        $t->deepEqual($body->build(), array("query" => array("bool" => array("filter" => array("match" => array("message" => 'this is a test'))))));
    }
    public function testBodybuilderShouldAddANotFilter()
    {
        $t->plan(1);
        $result = bodyBuilder()->notFilter('match', 'message', 'this is a test')->build();
        $t->deepEqual($result, array("query" => array("bool" => array("filter" => array("bool" => array("must_not" => array(array("match" => array("message" => 'this is a test')))))))));
    }
    public function testBodybuilderOrFilter()
    {
        $t->plan(1);
        $result = bodyBuilder()->filter('or', array(array("term" => array("user" => 'kimchy')), array("term" => array("user" => 'tony'))))->build();
        $t->deepEqual($result, array("query" => array("bool" => array("filter" => array("or" => array(array("term" => array("user" => 'kimchy')), array("term" => array("user" => 'tony'))))))));
    }
    public function testBodybuilderDynamicFilter()
    {
        $t->plan(1);
        $result = bodyBuilder()->filter('constant_score', function ($f) {
            $f->filter('term', 'user', 'kimchy');
        })->filter('term', 'message', 'this is a test')->build();
        $t->deepEqual($result, array("query" => array("bool" => array("filter" => array("bool" => array("must" => array(array("constant_score" => array("filter" => array("term" => array("user" => 'kimchy')))), array("term" => array("message" => 'this is a test')))))))));
    }
    public function testBodybuilderComplexDynamicFilter()
    {
        $t->plan(3);
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
        $t->deepEqual($result, array("query" => array("bool" => array("filter" => array("bool" => array("should" => array(array("bool" => array("must" => array(array("terms" => array("tags" => array('Popular'))), array("terms" => array("brands" => array('A', 'B')))))), array("bool" => array("must" => array(array("terms" => array("tags" => array('Emerging'))), array("terms" => array("brands" => array('C')))))), array("bool" => array("must" => array(array("terms" => array("tags" => array('Rumor'))), array("terms" => array("companies" => array('A', 'C', 'D')))))))))))));
        $t->deepEqual($result->query->bool->filter->bool->should, array(array("bool" => array("must" => array(array("terms" => array("tags" => array('Popular'))), array("terms" => array("brands" => array('A', 'B')))))), array("bool" => array("must" => array(array("terms" => array("tags" => array('Emerging'))), array("terms" => array("brands" => array('C')))))), array("bool" => array("must" => array(array("terms" => array("tags" => array('Rumor'))), array("terms" => array("companies" => array('A', 'C', 'D'))))))));
        $t->deepEqual($result->query->bool->filter->bool->should[0], array("bool" => array("must" => array(array("terms" => array("tags" => array('Popular'))), array("terms" => array("brands" => array('A', 'B')))))));
    }
    public function testBodybuilderMinimumShouldMatchFilter()
    {
        $t->plan(1);
        $result = bodyBuilder()->orFilter('term', 'user', 'kimchy')->orFilter('term', 'user', 'tony')->filterMinimumShouldMatch(2)->build();
        $t->deepEqual($result, array("query" => array("bool" => array("filter" => array("bool" => array("should" => array(array("term" => array("user" => 'kimchy')), array("term" => array("user" => 'tony'))), "minimum_should_match" => 2))))));
    }
    public function testBodybuilderMinimumShouldMatchQuery()
    {
        $t->plan(1);
        $result = bodyBuilder()->orQuery('term', 'user', 'kimchy')->orQuery('term', 'user', 'tony')->queryMinimumShouldMatch(2)->build();
        $t->deepEqual($result, array("query" => array("bool" => array("should" => array(array("term" => array("user" => 'kimchy')), array("term" => array("user" => 'tony'))), "minimum_should_match" => 2))));
    }
    public function testBodybuilderMinimumShouldMatchQueryAndFilter()
    {
        $t->plan(1);
        $result = bodyBuilder()->orQuery('term', 'user', 'kimchy')->orQuery('term', 'user', 'tony')->orFilter('term', 'user', 'kimchy')->orFilter('term', 'user', 'tony')->filterMinimumShouldMatch(1)->queryMinimumShouldMatch(2)->build();
        $t->deepEqual($result, array("query" => array("bool" => array("should" => array(array("term" => array("user" => 'kimchy')), array("term" => array("user" => 'tony'))), "minimum_should_match" => 2, "filter" => array("bool" => array("should" => array(array("term" => array("user" => 'kimchy')), array("term" => array("user" => 'tony'))), "minimum_should_match" => 1))))));
    }
}