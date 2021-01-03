<?php

use Best\ElasticSearch\BodyBuilder\AggregationBuilderTrait;
use Best\ElasticSearch\BodyBuilder\Test\BaseTestCase;
use Best\ElasticSearch\BodyBuilder\Test\FilterBuilderClass;
use Best\ElasticSearch\BodyBuilder\UtilTrait;

class AggregationBuilderClass
{
    use AggregationBuilderTrait, UtilTrait;
}

function aggregationBuilder()
{
    return new AggregationBuilderClass();
}

function filterBuilder()
{
    return new FilterBuilderClass();
}

class AggregationBuilderTest extends BaseTestCase
{
    public function testAggregationBuilderAvgAggregation()
    {
        $this->plan(1);
        $result = aggregationBuilder()->aggregation('avg', 'grade');
        $this->assertEquals($result->getAggregations(), ['agg_avg_grade' => ['avg' => ['field' => 'grade']]]);
    }

    public function testAggregationBuilderCardinalityAggregation()
    {
        $this->plan(1);
        $result = aggregationBuilder()->aggregation('cardinality', 'author');
        $this->assertEquals($result->getAggregations(), ['agg_cardinality_author' => ['cardinality' => ['field' => 'author']]]);
    }

    public function testAggregationBuilderExtendedStatsAggregation()
    {
        $this->plan(1);
        $result = aggregationBuilder()->aggregation('extended_stats', 'grade');
        $this->assertEquals($result->getAggregations(), ['agg_extended_stats_grade' => ['extended_stats' => ['field' => 'grade']]]);
    }

    public function testAggregationBuilderGeoBoundsAggregation()
    {
        $this->plan(1);
        $result = aggregationBuilder()->aggregation('geo_bounds', 'location');
        $this->assertEquals($result->getAggregations(), ['agg_geo_bounds_location' => ['geo_bounds' => ['field' => 'location']]]);
    }

    public function testAggregationBuilderGeoCentroidAggregation()
    {
        $this->plan(1);
        $result = aggregationBuilder()->aggregation('geo_centroid', 'location');
        $this->assertEquals($result->getAggregations(), ['agg_geo_centroid_location' => ['geo_centroid' => ['field' => 'location']]]);
    }

    public function testAggregationBuilderMaxAggregation()
    {
        $this->plan(1);
        $result = aggregationBuilder()->aggregation('max', 'price');
        $this->assertEquals($result->getAggregations(), ['agg_max_price' => ['max' => ['field' => 'price']]]);
    }

    public function testAggregationBuilderMinAggregation()
    {
        $this->plan(1);
        $result = aggregationBuilder()->aggregation('min', 'price');
        $this->assertEquals($result->getAggregations(), ['agg_min_price' => ['min' => ['field' => 'price']]]);
    }

    public function testAggregationBuilderPercentilesAggregation()
    {
        $this->plan(1);
        $result = aggregationBuilder()->aggregation('percentiles', 'load_time', ['percents' => [95, 99, 99.90000000000001]]);
        $this->assertEquals($result->getAggregations(), ['agg_percentiles_load_time' => ['percentiles' => ['field' => 'load_time', 'percents' => [95, 99, 99.90000000000001]]]]);
    }

    public function testAggregationBuilderPercentileRanksAggregation()
    {
        $this->plan(1);
        $result = aggregationBuilder()->aggregation('percentile_ranks', 'load_time', ['values' => [15, 30]]);
        $this->assertEquals($result->getAggregations(), ['agg_percentile_ranks_load_time' => ['percentile_ranks' => ['field' => 'load_time', 'values' => [15, 30]]]]);
    }

    public function testAggregationBuilderScriptedMetricAggregation()
    {
        $this->plan(1);
        $result = aggregationBuilder()->aggregation('scripted_metric', ['init_script' => 'params._agg.transactions = []', 'map_script' => "params._agg.transactions.add(doc.type.value == 'sale' ? doc.amount.value : -1 * doc.amount.value)", 'combine_script' => 'double profit = 0; for (t in params._agg.transactions) { profit += t } return profit', 'reduce_script' => 'double profit = 0; for (a in params._aggs) { profit += a } return profit'], 'agg_scripted_metric');
        $this->assertEquals($result->getAggregations(), ['agg_scripted_metric' => ['scripted_metric' => ['init_script' => 'params._agg.transactions = []', 'map_script' => "params._agg.transactions.add(doc.type.value == 'sale' ? doc.amount.value : -1 * doc.amount.value)", 'combine_script' => 'double profit = 0; for (t in params._agg.transactions) { profit += t } return profit', 'reduce_script' => 'double profit = 0; for (a in params._aggs) { profit += a } return profit']]]);
    }

    public function testAggregationBuilderStatsAggregation()
    {
        $this->plan(1);
        $result = aggregationBuilder()->aggregation('stats', 'grade');
        $this->assertEquals($result->getAggregations(), ['agg_stats_grade' => ['stats' => ['field' => 'grade']]]);
    }

    public function testAggregationBuilderSumAggregation()
    {
        $this->plan(1);
        $result = aggregationBuilder()->aggregation('sum', 'change');
        $this->assertEquals($result->getAggregations(), ['agg_sum_change' => ['sum' => ['field' => 'change']]]);
    }

    public function testAggregationBuilderValueCountAggregation()
    {
        $this->plan(1);
        $result = aggregationBuilder()->aggregation('value_count', 'grade');
        $this->assertEquals($result->getAggregations(), ['agg_value_count_grade' => ['value_count' => ['field' => 'grade']]]);
    }

    public function testAggregationBuilderChildrenAggregation()
    {
        $this->plan(1);
        $result = aggregationBuilder()->aggregation('terms', 'tags.keyword', ['size' => 10], 'top-tags', function ($a1) {
            return $a1->aggregation('children', ['type' => 'answer'], 'to-answers', function ($a2) {
                return $a2->aggregation('terms', 'owner.display_name.keyword', ['size' => 10], 'top-names');
            });
        });
        $this->assertEquals($result->getAggregations(), ['top-tags' => ['terms' => ['field' => 'tags.keyword', 'size' => 10], 'aggs' => ['to-answers' => ['children' => ['type' => 'answer'], 'aggs' => ['top-names' => ['terms' => ['field' => 'owner.display_name.keyword', 'size' => 10]]]]]]]);
    }

    public function testAggregationBuilderDateHistogramAggregation()
    {
        $this->plan(1);
        $result = aggregationBuilder()->aggregation('date_histogram', 'grade');
        $this->assertEquals($result->getAggregations(), ['agg_date_histogram_grade' => ['date_histogram' => ['field' => 'grade']]]);
    }

    public function testAggregationBuilderDateRangeAggregation()
    {
        $this->plan(1);
        $result = aggregationBuilder()->aggregation('date_range', 'date', ['format' => 'MM-yyy', 'ranges' => [['to' => 'now-10M/M'], ['from' => 'now-10M/M']]]);
        $this->assertEquals($result->getAggregations(), ['agg_date_range_date' => ['date_range' => ['field' => 'date', 'format' => 'MM-yyy', 'ranges' => [['to' => 'now-10M/M'], ['from' => 'now-10M/M']]]]]);
    }

    public function testAggregationBuilderDiversifiedSamplerAggregation()
    {
        $this->plan(1);
        $result = aggregationBuilder()->aggregation('diversified_sampler', 'user.id', ['shard_size' => 200], function ($a) {
            return $a->aggregation('significant_terms', 'text', 'keywords');
        });
        $this->assertEquals($result->getAggregations(), ['agg_diversified_sampler_user.id' => ['diversified_sampler' => ['field' => 'user.id', 'shard_size' => 200], 'aggs' => ['keywords' => ['significant_terms' => ['field' => 'text']]]]]);
    }

    public function testAggregationBuilderFilterAggregation()
    {
        $this->plan(1);
        $result = aggregationBuilder()->aggregation('filter', 'red_products', function ($a) {
            return $a->filter('term', 'color', 'red')->aggregation('avg', 'price', 'avg_price');
        });
        $this->assertEquals($result->getAggregations(), ['agg_filter_red_products' => ['filter' => ['term' => ['color' => 'red']], 'aggs' => ['avg_price' => ['avg' => ['field' => 'price']]]]]);
    }

    public function testAggregationBuilderFiltersAggregation()
    {
        $this->plan(1);
        $f1 = filterBuilder()->filter('term', 'user', 'John')->getFilter();
        $f2 = filterBuilder()->filter('term', 'status', 'failure')->getFilter();
        $result = aggregationBuilder()->aggregation('filters', ['filters' => ['users' => $f1, 'errors' => $f2]], 'agg_name');
        $this->assertEquals($result->getAggregations(), ['agg_name' => ['filters' => ['filters' => ['users' => ['term' => ['user' => 'John']], 'errors' => ['term' => ['status' => 'failure']]]]]]);
    }

    public function testAggregationBuilderPipelineAggregation()
    {
        $this->plan(1);
        $result = aggregationBuilder()->aggregation('date_histogram', 'date', ['interval' => 'month'], 'sales_per_month', function ($a) {
            return $a->aggregation('sum', 'price', 'sales');
        })->aggregation('max_bucket', ['buckets_path' => 'sales_per_month>sales'], 'max_monthly_sales');
        $this->assertEquals($result->getAggregations(), ['sales_per_month' => ['date_histogram' => ['field' => 'date', 'interval' => 'month'], 'aggs' => ['sales' => ['sum' => ['field' => 'price']]]], 'max_monthly_sales' => ['max_bucket' => ['buckets_path' => 'sales_per_month>sales']]]);
    }

    public function testAggregationBuilderMatrixStats()
    {
        $this->plan(1);
        $result = aggregationBuilder()->aggregation('matrix_stats', ['fields' => ['poverty', 'income']], 'matrixstats');
        $this->assertEquals($result->getAggregations(), ['matrixstats' => ['matrix_stats' => ['fields' => ['poverty', 'income']]]]);
    }
}
