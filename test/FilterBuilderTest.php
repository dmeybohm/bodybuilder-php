<?php

namespace Best\ElasticSearch\BodyBuilder\Test;

use Best\ElasticSearch\BodyBuilder\FilterBuilder;
use Best\ElasticSearch\BodyBuilder\FilterBuilderTrait;
use Best\ElasticSearch\BodyBuilder\QueryType;
use Best\ElasticSearch\BodyBuilder\UtilTrait;

class FilterBuilderClass {
    use FilterBuilderTrait, UtilTrait;
}

function filterBuilder() {
    return new FilterBuilderClass();
}

class FilterBuilderTest extends BaseTestCase
{
    public function testFilterBuilderFilterTerm()
    {
        $this->plan(1);
        $result = filterBuilder()->filter(QueryType::TERM, 'field', 'value');
        $this->assertEquals($result->getFilter(), array("term" => array("field" => 'value')));
    }
    public function testFilterBuilderFilterNested()
    {
        $this->plan(1);
        $result = filterBuilder()->filter(QueryType::CONSTANT_SCORE, function (FilterBuilder $f) {
            $f->filter(QueryType::TERM, 'field', 'value');
        });
        $this->assertEquals($result->getFilter(), array("constant_score" => array("filter" => array("term" => array("field" => 'value')))));
    }
}