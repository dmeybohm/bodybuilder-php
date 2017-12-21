'ImportDefaultSpecifier' not implemented. { type: 'ImportDefaultSpecifier',
  local: 
   { type: 'Identifier',
     name: 'test',
     range: [ 7, 11 ],
     loc: { start: [Object], end: [Object] } },
  range: [ 7, 11 ],
  loc: { start: { line: 1, column: 7 }, end: { line: 1, column: 11 } },
  parent: 
   { type: 'ImportDeclaration',
     specifiers: [ [Circular] ],
     source: 
      { type: 'Literal',
        value: 'tape',
        raw: '\'tape\'',
        range: [Array],
        loc: [Object] },
     range: [ 0, 24 ],
     loc: { start: [Object], end: [Object] },
     parent: 
      { type: 'Program',
        body: [Array],
        sourceType: 'module',
        range: [Array],
        loc: [Object],
        comments: [],
        tokens: [Array],
        scope: [Object] } } }
'ImportDefaultSpecifier' not implemented. { type: 'ImportDefaultSpecifier',
  local: 
   { type: 'Identifier',
     name: 'queryBuilder',
     range: [ 31, 43 ],
     loc: { start: [Object], end: [Object] } },
  range: [ 31, 43 ],
  loc: { start: { line: 2, column: 7 }, end: { line: 2, column: 19 } },
  parent: 
   { type: 'ImportDeclaration',
     specifiers: [ [Circular] ],
     source: 
      { type: 'Literal',
        value: '../src/query-builder',
        raw: '\'../src/query-builder\'',
        range: [Array],
        loc: [Object] },
     range: [ 24, 73 ],
     loc: { start: [Object], end: [Object] },
     parent: 
      { type: 'Program',
        body: [Array],
        sourceType: 'module',
        range: [Array],
        loc: [Object],
        comments: [],
        tokens: [Array],
        scope: [Object] } } }
<?php
test('queryBuilder | match_all', function ($t) {
$t->plan(1);
$result = queryBuilder()->query('match_all');
$t->deepEqual($result->getQuery(), array("match_all" => array()));
}
);
test('queryBuilder | match_all with boost', function ($t) {
$t->plan(1);
$result = queryBuilder()->query('match_all', array("boost" => 1.2));
$t->deepEqual($result->getQuery(), array("match_all" => array("boost" => 1.2)));
}
);
test('queryBuilder | match_none', function ($t) {
$t->plan(1);
$result = queryBuilder()->query('match_none');
$t->deepEqual($result->getQuery(), array("match_none" => array()));
}
);
test('queryBuilder | match', function ($t) {
$t->plan(1);
$result = queryBuilder()->query('match', 'message', 'this is a test');
$t->deepEqual($result->getQuery(), array("match" => array("message" => 'this is a test')));
}
);
test('queryBuilder | match empty string', function ($t) {
$t->plan(1);
$result = queryBuilder()->query('match', 'message', '');
$t->deepEqual($result->getQuery(), array("match" => array("message" => '')));
}
);
test('queryBuilder | match with options', function ($t) {
$t->plan(1);
$result = queryBuilder()->query('match', 'message', array("query" => 'this is a test', "operator" => 'and'));
$t->deepEqual($result->getQuery(), array("match" => array("message" => array("query" => 'this is a test', "operator" => 'and'))));
}
);
test('queryBuilder | match_phrase', function ($t) {
$t->plan(1);
$result = queryBuilder()->query('match_phrase', 'message', 'this is a test');
$t->deepEqual($result->getQuery(), array("match_phrase" => array("message" => 'this is a test')));
}
);
test('queryBuilder | match_phrase with options', function ($t) {
$t->plan(1);
$result = queryBuilder()->query('match_phrase', 'message', array("query" => 'this is a test', "analyzer" => 'my_analyzer'));
$t->deepEqual($result->getQuery(), array("match_phrase" => array("message" => array("query" => 'this is a test', "analyzer" => 'my_analyzer'))));
}
);
test('queryBuilder | common', function ($t) {
$t->plan(1);
$result = queryBuilder()->query('common', 'body', array("query" => 'this is bonsai cool', "cutoff_frequency" => 0.001));
$t->deepEqual($result->getQuery(), array("common" => array("body" => array("query" => 'this is bonsai cool', "cutoff_frequency" => 0.001))));
}
);
test('queryBuilder | common', function ($t) {
$t->plan(1);
$result = queryBuilder()->query('common', 'body', array("query" => 'this is bonsai cool', "cutoff_frequency" => 0.001));
$t->deepEqual($result->getQuery(), array("common" => array("body" => array("query" => 'this is bonsai cool', "cutoff_frequency" => 0.001))));
}
);
test('queryBuilder | query_string', function ($t) {
$t->plan(1);
$result = queryBuilder()->query('query_string', 'query', 'this AND that OR thus');
$t->deepEqual($result->getQuery(), array("query_string" => array("query" => 'this AND that OR thus')));
}
);
test('queryBuilder | query_string with options', function ($t) {
$t->plan(1);
$result = queryBuilder()->query('query_string', 'query', 'this AND that OR thus', array("fields" => array('content', 'name')));
$t->deepEqual($result->getQuery(), array("query_string" => array("query" => 'this AND that OR thus', "fields" => array('content', 'name'))));
}
);
test('queryBuilder | query_string alternative', function ($t) {
$t->plan(1);
$result = queryBuilder()->query('query_string', array("query" => 'this AND that OR thus', "fields" => array('content', 'name')));
$t->deepEqual($result->getQuery(), array("query_string" => array("query" => 'this AND that OR thus', "fields" => array('content', 'name'))));
}
);
test('queryBuilder | simple_query_string', function ($t) {
$t->plan(1);
$result = queryBuilder()->query('simple_query_string', 'query', 'foo bar baz');
$t->deepEqual($result->getQuery(), array("simple_query_string" => array("query" => 'foo bar baz')));
}
);
test('queryBuilder | term', function ($t) {
$t->plan(1);
$result = queryBuilder()->query('term', 'user', 'kimchy');
$t->deepEqual($result->getQuery(), array("term" => array("user" => 'kimchy')));
}
);
test('queryBuilder | term with boost', function ($t) {
$t->plan(1);
$result = queryBuilder()->query('term', 'status', array("value" => 'urgent', "boost" => '2.0'));
$t->deepEqual($result->getQuery(), array("term" => array("status" => array("value" => 'urgent', "boost" => '2.0'))));
}
);
test('queryBuilder | term multiple', function ($t) {
$t->plan(1);
$result = queryBuilder()->orQuery('term', 'status', array("value" => 'urgent', "boost" => '2.0'))->orQuery('term', 'status', 'normal');
$t->deepEqual($result->getQuery(), array("bool" => array("should" => array(array("term" => array("status" => array("value" => 'urgent', "boost" => '2.0'))), array("term" => array("status" => 'normal'))))));
}
);
test('queryBuilder | terms', function ($t) {
$t->plan(1);
$result = queryBuilder()->query('terms', 'user', array('kimchy', 'elastic'));
$t->deepEqual($result->getQuery(), array("terms" => array("user" => array('kimchy', 'elastic'))));
}
);
test('queryBuilder | range', function ($t) {
$t->plan(1);
$result = queryBuilder()->query('range', 'age', array("gte" => 10));
$t->deepEqual($result->getQuery(), array("range" => array("age" => array("gte" => 10))));
}
);
test('queryBuilder | exists', function ($t) {
$t->plan(1);
$result = queryBuilder()->query('exists', 'user');
$t->deepEqual($result->getQuery(), array("exists" => array("field" => 'user')));
}
);
test('queryBuilder | missing', function ($t) {
$t->plan(1);
$result = queryBuilder()->query('missing', 'user');
$t->deepEqual($result->getQuery(), array("missing" => array("field" => 'user')));
}
);
test('queryBuilder | prefix', function ($t) {
$t->plan(1);
$result = queryBuilder()->query('prefix', 'user', 'ki');
$t->deepEqual($result->getQuery(), array("prefix" => array("user" => 'ki')));
}
);
test('queryBuilder | prefix with boost', function ($t) {
$t->plan(1);
$result = queryBuilder()->query('prefix', 'user', array("value" => 'ki', "boost" => 2));
$t->deepEqual($result->getQuery(), array("prefix" => array("user" => array("value" => 'ki', "boost" => 2))));
}
);
test('queryBuilder | wildcard', function ($t) {
$t->plan(1);
$result = queryBuilder()->query('wildcard', 'user', 'ki*y');
$t->deepEqual($result->getQuery(), array("wildcard" => array("user" => 'ki*y')));
}
);
test('queryBuilder | regexp', function ($t) {
$t->plan(1);
$result = queryBuilder()->query('regexp', 'name.first', 's.*y');
$t->deepEqual($result->getQuery(), array("regexp" => array("name.first" => 's.*y')));
}
);
test('queryBuilder | fuzzy', function ($t) {
$t->plan(1);
$result = queryBuilder()->query('fuzzy', 'user', 'ki');
$t->deepEqual($result->getQuery(), array("fuzzy" => array("user" => 'ki')));
}
);
test('queryBuilder | type', function ($t) {
$t->plan(1);
$result = queryBuilder()->query('type', 'value', 'my_type');
$t->deepEqual($result->getQuery(), array("type" => array("value" => 'my_type')));
}
);
test('queryBuilder | ids', function ($t) {
$t->plan(1);
$result = queryBuilder()->query('ids', 'type', 'my_ids', array("values" => array('1', '4', '100')));
$t->deepEqual($result->getQuery(), array("ids" => array("type" => 'my_ids', "values" => array('1', '4', '100'))));
}
);
test('queryBuilder | constant_score', function ($t) {
$t->plan(1);
$result = queryBuilder()->query('constant_score', array("boost" => 1.2), function ($q) {
return $q->filter('term', 'user', 'kimchy');
}
);
$t->deepEqual($result->getQuery(), array("constant_score" => array("filter" => array("term" => array("user" => 'kimchy')), "boost" => 1.2)));
}
);
test('queryBuilder | nested', function ($t) {
$t->plan(1);
$result = queryBuilder()->query('nested', array("path" => 'obj1', "score_mode" => 'avg'), function ($q) {
return $q->query('match', 'obj1.name', 'blue')->query('range', 'obj1.count', array("gt" => 5));
}
);
$t->deepEqual($result->getQuery(), array("nested" => array("path" => 'obj1', "score_mode" => 'avg', "query" => array("bool" => array("must" => array(array("match" => array("obj1.name" => 'blue')), array("range" => array("obj1.count" => array("gt" => 5)))))))));
}
);
test('queryBuilder | has_child', function ($t) {
$t->plan(1);
$result = queryBuilder()->query('has_child', 'type', 'blog_tag', function ($q) {
return $q->query('term', 'tag', 'something');
}
);
$t->deepEqual($result->getQuery(), array("has_child" => array("type" => 'blog_tag', "query" => array("term" => array("tag" => 'something')))));
}
);
test('queryBuilder | has_parent', function ($t) {
$t->plan(1);
$result = queryBuilder()->query('has_parent', 'parent_tag', 'blog', function ($q) {
return $q->query('term', 'tag', 'something');
}
);
$t->deepEqual($result->getQuery(), array("has_parent" => array("parent_tag" => 'blog', "query" => array("term" => array("tag" => 'something')))));
}
);
test('queryBuilder | geo_bounding_box', function ($t) {
$t->plan(1);
$result = queryBuilder()->query('geo_bounding_box', 'pin.location', array("top_left" => array("lat" => 40, "lon" => -74), "bottom_right" => array("lat" => 40, "lon" => -74)), array("relation" => 'within'));
$t->deepEqual($result->getQuery(), array("geo_bounding_box" => array("relation" => 'within', "pin.location" => array("top_left" => array("lat" => 40, "lon" => -74), "bottom_right" => array("lat" => 40, "lon" => -74)))));
}
);
test('queryBuilder | geo_distance', function ($t) {
$t->plan(1);
$result = queryBuilder()->query('geo_distance', 'pin.location', array("lat" => 40, "lon" => -74), array("distance" => '200km'));
$t->deepEqual($result->getQuery(), array("geo_distance" => array("distance" => '200km', "pin.location" => array("lat" => 40, "lon" => -74))));
}
);
test('queryBuilder | geo_distance_range', function ($t) {
$t->plan(1);
$result = queryBuilder()->query('geo_distance_range', 'pin.location', array("lat" => 40, "lon" => -74), array("from" => '100km', "to" => '200km'));
$t->deepEqual($result->getQuery(), array("geo_distance_range" => array("from" => '100km', "to" => '200km', "pin.location" => array("lat" => 40, "lon" => -74))));
}
);
test('queryBuilder | geo_polygon', function ($t) {
$t->plan(1);
$result = queryBuilder()->query('geo_polygon', 'person.location', array("points" => array(array("lat" => 40, "lon" => -70), array("lat" => 30, "lon" => -80), array("lat" => 20, "lon" => -90))));
$t->deepEqual($result->getQuery(), array("geo_polygon" => array("person.location" => array("points" => array(array("lat" => 40, "lon" => -70), array("lat" => 30, "lon" => -80), array("lat" => 20, "lon" => -90))))));
}
);
test('queryBuilder | geohash_cell', function ($t) {
$t->plan(1);
$result = queryBuilder()->query('geohash_cell', 'pin', array("lat" => 13.4080, "lon" => 52.5186), array("precision" => 3, "neighbors" => true));
$t->deepEqual($result->getQuery(), array("geohash_cell" => array("pin" => array("lat" => 13.4080, "lon" => 52.5186), "precision" => 3, "neighbors" => true)));
}
);
test('queryBuilder | more_like_this', function ($t) {
$t->plan(1);
$result = queryBuilder()->query('more_like_this', array("fields" => array('title', 'description'), "like" => "Once upon a time", "min_term_freq" => 1, "max_query_terms" => 12));
$t->deepEqual($result->getQuery(), array("more_like_this" => array("fields" => array('title', 'description'), "like" => "Once upon a time", "min_term_freq" => 1, "max_query_terms" => 12)));
}
);
test('queryBuilder | template', function ($t) {
$t->plan(1);
$result = queryBuilder()->query('template', array("inline" => array("match" => array("text" => '{{query_string}}')), "params" => array("query_string" => 'all about search')));
$t->deepEqual($result->getQuery(), array("template" => array("inline" => array("match" => array("text" => '{{query_string}}')), "params" => array("query_string" => 'all about search'))));
}
);
test('queryBuilder | script', function ($t) {
$t->plan(1);
$result = queryBuilder()->query('script', 'script', array("inline" => "doc['num1'].value > 1", "lang" => 'painless'));
$t->deepEqual($result->getQuery(), array("script" => array("script" => array("inline" => "doc['num1'].value > 1", "lang" => 'painless'))));
}
);
test('queryBuilder | or', function ($t) {
$t->plan(1);
$result = queryBuilder()->query('or', array(array("term" => array("user" => 'kimchy')), array("term" => array("user" => 'tony'))));
$t->deepEqual($result->getQuery(), array("or" => array(array("term" => array("user" => 'kimchy')), array("term" => array("user" => 'tony')))));
}
);
test('queryBuilder | minimum_should_match with one query ignores minimum', function ($t) {
$t->plan(1);
$result = queryBuilder()->orQuery('term', 'status', 'alert')->queryMinimumShouldMatch(2);
$t->deepEqual($result->getQuery(), array("bool" => array("should" => array(array("term" => array("status" => 'alert'))))));
}
);
test('queryBuilder | minimum_should_match with multiple combination', function ($t) {
$t->plan(1);
$result = queryBuilder()->orQuery('term', 'status', 'alert')->orQuery('term', 'status', 'normal')->queryMinimumShouldMatch('2<-25% 9<-3');
$t->deepEqual($result->getQuery(), array("bool" => array("should" => array(array("term" => array("status" => 'alert')), array("term" => array("status" => 'normal'))), "minimum_should_match" => '2<-25% 9<-3')));
}
);
test('queryBuilder | minimum_should_match with multiple queries', function ($t) {
$t->plan(1);
$result = queryBuilder()->orQuery('term', 'status', 'alert')->orQuery('term', 'status', 'normal')->queryMinimumShouldMatch(2);
$t->deepEqual($result->getQuery(), array("bool" => array("should" => array(array("term" => array("status" => 'alert')), array("term" => array("status" => 'normal'))), "minimum_should_match" => 2)));
}
);

