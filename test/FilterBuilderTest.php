<?php

class FilterBuilderTest extends \PHPUnit\Framework\TestCase
{
    public function testFilterBuilderFilterTerm()
    {
        $t->plan(1);
        $result = filterBuilder()->filter('term', 'field', 'value');
        $t->deepEqual($result->getFilter(), array("term" => array("field" => 'value')));
    }
    public function testFilterBuilderFilterNested()
    {
        $t->plan(1);
        $result = filterBuilder()->filter('constant_score', function ($f) {
            $f->filter('term', 'field', 'value');
        });
        $t->deepEqual($result->getFilter(), array("constant_score" => array("filter" => array("term" => array("field" => 'value')))));
    }
}