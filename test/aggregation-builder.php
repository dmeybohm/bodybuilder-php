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
        comments: [Array],
        tokens: [Array],
        scope: [Object] } } }
'ImportDefaultSpecifier' not implemented. { type: 'ImportDefaultSpecifier',
  local: 
   { type: 'Identifier',
     name: 'aggregationBuilder',
     range: [ 31, 49 ],
     loc: { start: [Object], end: [Object] } },
  range: [ 31, 49 ],
  loc: { start: { line: 2, column: 7 }, end: { line: 2, column: 25 } },
  parent: 
   { type: 'ImportDeclaration',
     specifiers: [ [Circular] ],
     source: 
      { type: 'Literal',
        value: '../src/aggregation-builder',
        raw: '\'../src/aggregation-builder\'',
        range: [Array],
        loc: [Object] },
     range: [ 24, 84 ],
     loc: { start: [Object], end: [Object] },
     parent: 
      { type: 'Program',
        body: [Array],
        sourceType: 'module',
        range: [Array],
        loc: [Object],
        comments: [Array],
        tokens: [Array],
        scope: [Object] } } }
'ImportDefaultSpecifier' not implemented. { type: 'ImportDefaultSpecifier',
  local: 
   { type: 'Identifier',
     name: 'filterBuilder',
     range: [ 91, 104 ],
     loc: { start: [Object], end: [Object] } },
  range: [ 91, 104 ],
  loc: { start: { line: 3, column: 7 }, end: { line: 3, column: 20 } },
  parent: 
   { type: 'ImportDeclaration',
     specifiers: [ [Circular] ],
     source: 
      { type: 'Literal',
        value: '../src/filter-builder',
        raw: '\'../src/filter-builder\'',
        range: [Array],
        loc: [Object] },
     range: [ 84, 135 ],
     loc: { start: [Object], end: [Object] },
     parent: 
      { type: 'Program',
        body: [Array],
        sourceType: 'module',
        range: [Array],
        loc: [Object],
        comments: [Array],
        tokens: [Array],
        scope: [Object] } } }
<?php
test('aggregationBuilder | avg aggregation', function ($t) {
$t->plan(1);
$result = aggregationBuilder()->aggregation('avg', 'grade');
$t->deepEqual($result->getAggregations(), array("agg_avg_grade" => array("avg" => array("field" => 'grade'))));
}
);
test('aggregationBuilder | cardinality aggregation', function ($t) {
$t->plan(1);
$result = aggregationBuilder()->aggregation('cardinality', 'author');
$t->deepEqual($result->getAggregations(), array("agg_cardinality_author" => array("cardinality" => array("field" => 'author'))));
}
);
test('aggregationBuilder | extended_stats aggregation', function ($t) {
$t->plan(1);
$result = aggregationBuilder()->aggregation('extended_stats', 'grade');
$t->deepEqual($result->getAggregations(), array("agg_extended_stats_grade" => array("extended_stats" => array("field" => 'grade'))));
}
);
test('aggregationBuilder | geo_bounds aggregation', function ($t) {
$t->plan(1);
$result = aggregationBuilder()->aggregation('geo_bounds', 'location');
$t->deepEqual($result->getAggregations(), array("agg_geo_bounds_location" => array("geo_bounds" => array("field" => 'location'))));
}
);
test('aggregationBuilder | geo_centroid aggregation', function ($t) {
$t->plan(1);
$result = aggregationBuilder()->aggregation('geo_centroid', 'location');
$t->deepEqual($result->getAggregations(), array("agg_geo_centroid_location" => array("geo_centroid" => array("field" => 'location'))));
}
);
test('aggregationBuilder | max aggregation', function ($t) {
$t->plan(1);
$result = aggregationBuilder()->aggregation('max', 'price');
$t->deepEqual($result->getAggregations(), array("agg_max_price" => array("max" => array("field" => 'price'))));
}
);
test('aggregationBuilder | min aggregation', function ($t) {
$t->plan(1);
$result = aggregationBuilder()->aggregation('min', 'price');
$t->deepEqual($result->getAggregations(), array("agg_min_price" => array("min" => array("field" => 'price'))));
}
);
test('aggregationBuilder | percentiles aggregation', function ($t) {
$t->plan(1);
$result = aggregationBuilder()->aggregation('percentiles', 'load_time', array("percents" => array(95, 99, 99.9)));
$t->deepEqual($result->getAggregations(), array("agg_percentiles_load_time" => array("percentiles" => array("field" => 'load_time', "percents" => array(95, 99, 99.9)))));
}
);
$test->skip('aggregationBuilder | percentiles script aggregation', function ($t) {
$t->plan(1);
$result = aggregationBuilder()->aggregation('percentiles', 'load_time', array("script" => array("inline" => "doc['load_time'].value / timeUnit", "params" => array("timeUnit" => 100))));
$t->deepEqual($result->getAggregations(), array("agg_percentiles_load_time" => array("percentiles" => array("script" => array("inline" => "doc['load_time'].value / timeUnit", "params" => array("timeUnit" => 100))))));
}
);
test('aggregationBuilder | percentile_ranks aggregation', function ($t) {
$t->plan(1);
$result = aggregationBuilder()->aggregation('percentile_ranks', 'load_time', array("values" => array(15, 30)));
$t->deepEqual($result->getAggregations(), array("agg_percentile_ranks_load_time" => array("percentile_ranks" => array("field" => 'load_time', "values" => array(15, 30)))));
}
);
test('aggregationBuilder | scripted_metric aggregation', function ($t) {
$t->plan(1);
$result = aggregationBuilder()->aggregation('scripted_metric', array("init_script" => "params._agg.transactions = []", "map_script" => "params._agg.transactions.add(doc.type.value == 'sale' ? doc.amount.value : -1 * doc.amount.value)", "combine_script" => "double profit = 0; for (t in params._agg.transactions) { profit += t } return profit", "reduce_script" => "double profit = 0; for (a in params._aggs) { profit += a } return profit"), 'agg_scripted_metric');
$t->deepEqual($result->getAggregations(), array("agg_scripted_metric" => array("scripted_metric" => array("init_script" => "params._agg.transactions = []", "map_script" => "params._agg.transactions.add(doc.type.value == 'sale' ? doc.amount.value : -1 * doc.amount.value)", "combine_script" => "double profit = 0; for (t in params._agg.transactions) { profit += t } return profit", "reduce_script" => "double profit = 0; for (a in params._aggs) { profit += a } return profit"))));
}
);
test('aggregationBuilder | stats aggregation', function ($t) {
$t->plan(1);
$result = aggregationBuilder()->aggregation('stats', 'grade');
$t->deepEqual($result->getAggregations(), array("agg_stats_grade" => array("stats" => array("field" => 'grade'))));
}
);
test('aggregationBuilder | sum aggregation', function ($t) {
$t->plan(1);
$result = aggregationBuilder()->aggregation('sum', 'change');
$t->deepEqual($result->getAggregations(), array("agg_sum_change" => array("sum" => array("field" => 'change'))));
}
);
test('aggregationBuilder | value_count aggregation', function ($t) {
$t->plan(1);
$result = aggregationBuilder()->aggregation('value_count', 'grade');
$t->deepEqual($result->getAggregations(), array("agg_value_count_grade" => array("value_count" => array("field" => 'grade'))));
}
);
test('aggregationBuilder | children aggregation', function ($t) {
$t->plan(1);
$result = aggregationBuilder()->aggregation('terms', 'tags.keyword', array("size" => 10), 'top-tags', function ($a1) {
return $a1->aggregation('children', array("type" => 'answer'), 'to-answers', function ($a2) {
return $a2->aggregation('terms', 'owner.display_name.keyword', array("size" => 10), 'top-names');
}
);
}
);
$t->deepEqual($result->getAggregations(), array("top-tags" => array("terms" => array("field" => 'tags.keyword', "size" => 10), "aggs" => array("to-answers" => array("children" => array("type" => 'answer'), "aggs" => array("top-names" => array("terms" => array("field" => 'owner.display_name.keyword', "size" => 10))))))));
}
);
test('aggregationBuilder | date_histogram aggregation', function ($t) {
$t->plan(1);
$result = aggregationBuilder()->aggregation('date_histogram', 'grade');
$t->deepEqual($result->getAggregations(), array("agg_date_histogram_grade" => array("date_histogram" => array("field" => 'grade'))));
}
);
test('aggregationBuilder | date_range aggregation', function ($t) {
$t->plan(1);
$result = aggregationBuilder()->aggregation('date_range', 'date', array("format" => 'MM-yyy', "ranges" => array(array("to" => 'now-10M/M'), array("from" => 'now-10M/M'))));
$t->deepEqual($result->getAggregations(), array("agg_date_range_date" => array("date_range" => array("field" => 'date', "format" => 'MM-yyy', "ranges" => array(array("to" => 'now-10M/M'), array("from" => 'now-10M/M'))))));
}
);
test('aggregationBuilder | diversified_sampler aggregation', function ($t) {
$t->plan(1);
$result = aggregationBuilder()->aggregation('diversified_sampler', 'user.id', array("shard_size" => 200), function ($a) {
return $a->aggregation('significant_terms', 'text', 'keywords');
}
);
$t->deepEqual($result->getAggregations(), array("agg_diversified_sampler_user.id" => array("diversified_sampler" => array("field" => 'user.id', "shard_size" => 200), "aggs" => array("keywords" => array("significant_terms" => array("field" => 'text'))))));
}
);
test('aggregationBuilder | filter aggregation', function ($t) {
$t->plan(1);
$result = aggregationBuilder()->aggregation('filter', 'red_products', function ($a) {
return $a->filter('term', 'color', 'red')->aggregation('avg', 'price', 'avg_price');
}
);
$t->deepEqual($result->getAggregations(), array("agg_filter_red_products" => array("filter" => array("term" => array("color" => 'red')), "aggs" => array("avg_price" => array("avg" => array("field" => 'price'))))));
}
);
test('aggregationBuilder | filters aggregation', function ($t) {
$t->plan(1);
$f1 = filterBuilder()->filter('term', 'user', 'John')->getFilter();
$f2 = filterBuilder()->filter('term', 'status', 'failure')->getFilter();
$result = aggregationBuilder()->aggregation('filters', array("filters" => array("users" => $f1, "errors" => $f2)), 'agg_name');
$t->deepEqual($result->getAggregations(), array("agg_name" => array("filters" => array("filters" => array("users" => array("term" => array("user" => 'John')), "errors" => array("term" => array("status" => 'failure')))))));
}
);
test('aggregationBuilder | pipeline aggregation', function ($t) {
$t->plan(1);
$result = aggregationBuilder()->aggregation('date_histogram', 'date', array("interval" => 'month'), 'sales_per_month', function ($a) {
return $a->aggregation('sum', 'price', 'sales');
}
)->aggregation('max_bucket', array("buckets_path" => 'sales_per_month>sales'), 'max_monthly_sales');
$t->deepEqual($result->getAggregations(), array("sales_per_month" => array("date_histogram" => array("field" => 'date', "interval" => 'month'), "aggs" => array("sales" => array("sum" => array("field" => 'price')))), "max_monthly_sales" => array("max_bucket" => array("buckets_path" => 'sales_per_month>sales'))));
}
);
test('aggregationBuilder | matrix stats', function ($t) {
$t->plan(1);
$result = aggregationBuilder()->aggregation('matrix_stats', array("fields" => array('poverty', 'income')), 'matrixstats');
$t->deepEqual($result->getAggregations(), array("matrixstats" => array("matrix_stats" => array("fields" => array('poverty', 'income')))));
}
);

